<?php
namespace Data\Providers;

class DB extends \mysqli
{
    public $query;
    /** @var  \mysqli_result */
    public $result;
    public $query_list;
    public $config;
    public $in_transaction = false;

    private static $instances;

    public $check_connection = false;

    public function __construct($name)
    {
        $databases = Config::instance("databases");
        $this->config = $databases->get($name);
        $this->establish();
    }

    public function establish()
    {
        @parent::__construct($this->config['host'], $this->config['username'], $this->config['password'], $this->config['database']);

        if ($this->connect_errno > 0) {
            throw new \Exception($this->connect_error, $this->connect_errno);
        }

        $this->set_charset($this->config['encoding']);
    }

    /**
     * @static
     * @param string $name
     * @return DB
     */
    public static function instance($name = "main")
    {
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name);
        }
        return self::$instances[$name];
    }

    /**
     * Запрос к базе данных
     *
     * @param Query|string $query Запрос
     * @throws \Exception
     * @return \mysqli_result
     */
    public function query($query)
    {
        if ($this->check_connection) {
            if (!parent::ping()) {
                $this->establish();
            }
        }
        if ($query instanceof query) {
            $this->query = $query->sql();
        }
        else {
            $this->query = $query;
        }

        $this->query_list[] = $this->query;

        $this->result = parent::query($this->query);

        if ($this->error) {
            throw new \Exception("MYSQL ERROR: {$this->error}.\nSQL: {$this->query}");
        }

        return $this->result;
    }

    /**
     * Выполняет запрос к БД и возвращает первую строчку результата
     *
     * @param string        $query
     * @param null|string   $field
     * @return array
     */
    public function queryRow($query, $field = null)
    {
        $this->query($query);
        $row = $this->fetchRow();
        if (!is_null($field)) {
            return $row[$field];
        } else {
            return $row;
        }
    }

    public function queryRows($query, $primary_key = "", $field = "", $multiple = false)
    {
        $this->query($query);

        $list = array();
        while($row = $this->fetchRow()) {
            if ($primary_key) {
                if ($multiple) {
                    $list[$row[$primary_key]][] = $field ? $row[$field] : $row;
                } else {
                    $list[$row[$primary_key]] = $field ? $row[$field] : $row;
                }
            }
            else {
                $list[] = $field ? $row[$field] : $row;
            }
        }
        return $list;
    }

    /**
    * Возвращает количество строк в таблице/выборке
    *
    * @param string|query $table название таблицы или объект запроса
    * @param string $where условие выборки
    * @return int
    */
    public function queryCount($table, $where = "")
    {
        if ($table instanceof query) {
            $query = $table->count_sql();
        }
        else {
            $query = "SELECT COUNT(*) as count FROM {$table}";
            if ($where) {
                $query .= " WHERE $where";
            }
        }
        return $this->queryRow($query, "count");
    }

    /**
    * Возвращает строку в виде ассоциативного массива из результата последнего запроса к БД
    *
    * @param \mysqli_result $result альтеранативный результат
    * @return array
    */
    public function fetchRow($result = null)
    {
        if ($result) {
            return $result->fetch_assoc();
        }
        else if ($this->result) {
            return $this->result->fetch_assoc();
        }
        else {
            return null;
        }
    }

    /**
     * Возвращает объект запроса (новый)
     */
    public function getQuery()
    {
        return new Query($this);
    }

    /**
    * Обновляет записи в таблице
    *
    * @param array $data массив вида поле => значение
    * @param string $table исходная таблица
    * @param string $where условие выборки для обновления
    * @return int количество обновленных строк
    */
    public function update(array $data, $table, $where)
    {
        if (empty($data)) return false;

        $pairs = array();
        foreach ($data as $key => $value) {
            if (is_null($value) || $value === "NULL") {
                $value = "NULL";
            }
            else {
                $value = "'".$this->escape($value)."'";
            }
            $pairs[] = "`$key` = $value";
        }

        $this->query("UPDATE `$table` SET ".implode(", ", $pairs)." WHERE $where");

        return $this->affected_rows;
    }

    /**
    * Добавляет записи в таблицу
    *
    * @param array $data массив вида поле => значение
    * @param string $table исходная таблица
    * @return int количество добавленных строк
    */
    public function insert(array $data, $table)
    {
        $keys = array();
        $values = array();

        foreach ($data as $key => $value) {
            if (is_null($value) || $value === "NULL") {
                $value = "NULL";
            } else {
                $value = "'".$this->escape($value)."'";
            }
            $keys[] = "`$key`";
            $values[] = $value;
        }

        $this->query("INSERT INTO `$table` (".implode(", ", $keys).") VALUES (".implode(", ", $values).")");

        return $this->insert_id;
    }

    /**
     * @param array $data
     * @param string $table
     * @param string $primary_field
     * @return mixed Значение первичного ключа
     */
    public function store(array $data, $table, $primary_field = "id")
    {
        if (!empty($data[$primary_field])) {
            $id = $data[$primary_field];
            unset($data[$primary_field]);
            $this->update($data, $table, "`{$primary_field}` = '{$id}'");
        }
        else {
            $this->insert($data, $table);
            $id = $this->insert_id;
        }
        return $id;
    }

    public function delete($table, $where, $limit = 0)
    {
        $query = "DELETE FROM {$table}";
        if ($where) $query .= " WHERE $where";
        if ($limit) $query .= " LIMIT $limit";
        $this->query($query);
    }

    public function escape($str)
    {
        return $this->real_escape_string($str);
    }

    public function escapeArray($var)
    {
        if (!is_numeric($var)) {
            $var = $this->escape($var);
        }
        else {
            $var = (int) $var;
        }
        return $var;
    }

    public function startTransaction() {
        $this->query("START TRANSACTION");
        $this->in_transaction = true;
    }

    public function commitTransaction() {
        $this->query("COMMIT");
        $this->in_transaction = false;
    }

    public function rollbackTransaction() {
        $this->query("ROLLBACK");
        $this->in_transaction = false;
    }
}