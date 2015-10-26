<?php
namespace Data\Providers;

class Query
{
    protected $db;

    protected $select = array();
    protected $from = "";
    protected $where = array();
    protected $group = array();
    protected $having = array();
    protected $order = array();
    protected $limit = 0;
    protected $offset = 0;
    protected $join = array();

    protected $update = "";
    protected $values = array();

    protected $insert = "";

    protected $data = array();

    /** @var null|\mysqli_result */
    public $result = null;

    public $where_type = "AND";

    public function __construct(db &$db)
    {
        if (!$db) {
            throw new \Exception("DB connection required for creating query");
        }
        $this->db = $db;
    }

    public function select($str)
    {
        $this->select[] = $str;
        return $this;
    }

    public function from($table)
    {
        $this->from = $table;
        return $this;
    }

    public function left_join($table, $cond)
    {
        $this->join['left'][] = "$table ON $cond";
        return $this;
    }

    /**
     * Add WHERE clause
     * @param string $str Clause
     * @param mixed  $_val [optional] Clause value<br>
     * Escape Clause value, if value of string, except NULL, SQL functions
     * @return Query
     */
    public function where($str, $_val = null)
    {
        if ( $_val !== null ) {
            $arg_list = array_slice(func_get_args(), 1);

            $arg_list = array_map(array($this->db, 'escapeArray'), $arg_list);

            $str = vsprintf($str, $arg_list);
        }
        $this->where[] = $str;
        return $this;
    }

    public function group($str)
    {
        $this->group[] = $str;
        return $this;
    }

    public function having($str)
    {
        $this->having[] = $str;
        return $this;
    }

    public function order($fields, $order = "")
    {
        $str = $fields;
        if ($order) {
            $str .= " ".$order;
        }
        $this->order[] = $str;
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = (int) $limit;
        $this->offset = (int) $offset;
        return $this;
    }

    public function update($table)
    {
        $this->update = $table;
        return $this;
    }

    public function insert($table)
    {
        $this->insert = $table;
        return $this;
    }

    public function set(array $array)
    {
        $this->values = $array;
        return $this;
    }

    public function set_expr($key, $expr)
    {
        $this->data[] = "`$key` = $expr";
        return $this;
    }

    public function values(array $array)
    {
        $this->values = $array;
        return $this;
    }

    public function sql()
    {
        $sql = "";
        if ($this->select) {
            $sql .= "SELECT ".implode(",", $this->select);
            if ($this->from) {
                $sql .= " FROM {$this->from}";
            }
            if (isset($this->join['left'])) {
                $sql .= " LEFT JOIN ".implode(" LEFT JOIN ", $this->join['left']);
            }
            if ($this->where) {
                $sql .= " WHERE ".implode(" ".$this->where_type." ", $this->where);
            }
            if ($this->group) {
                $sql .= " GROUP BY ".implode(",", $this->group);
            }
            if ($this->having) {
                $sql .= " HAVING ".implode(" ".$this->where_type." ", $this->having);
            }
            if ($this->order) {
                $sql .= " ORDER BY ".implode(",", $this->order);
            }
            if ($this->limit) {
                $sql .= " LIMIT {$this->limit}";
                if ($this->offset) {
                    $sql .= " OFFSET {$this->offset}";
                }
            }
        }
        elseif ($this->update) {
            $sql .= "UPDATE {$this->update}";

            if ($this->values || $this->data) {
                foreach ($this->values as $key => $value) {
                    $this->data[] = "`$key` = ".$this->parse_value($value);
                }
                $sql .= " SET ".implode(",", $this->data);
            }
            else {
                throw new \Exception("No values for update");
            }

            if ($this->where) {
                $sql .= " WHERE ".implode(" ".$this->where_type." ", $this->where);
            }
        }
        elseif ($this->insert) {
            $sql .= "INSERT INTO ";
            if ($this->values) {
                $keys = array();
                $values = array();
                foreach ($this->values as $key => $value) {
                    $keys[] = "`$key`";
                    $values[] = $this->parse_value($value);
                }
                $sql .= " (".implode(",", $keys).") VALUES (".implode(",", $values).")";
            }
            else {
                throw new \Exception("No values for insert");
            }
        }
        else {
            throw new \Exception("Error while build query", 500);
        }

        return $sql;
    }

    public function count_sql()
    {
        $sql = "SELECT COUNT(*) as count";
        if ($this->from) {
            $sql .= " FROM {$this->from}";
        }
        if ($this->where) {
            $sql .= " WHERE ".implode(" ".$this->where_type." ", $this->where);
        }
        if ($this->group) {
            $sql .= " GROUP BY ".implode(",", $this->group);
        }
        if ($this->having) {
            $sql .= " HAVING ".implode(" ".$this->where_type." ", $this->having);
        }
        return $sql;
    }

    public function execute()
    {
        $this->result = $this->db->query($this);
        return $this->result;
    }

    public function fetch_row()
    {
        return $this->result ? $this->result->fetch_assoc() : null;
    }

    protected function parse_value($value)
    {
        if (is_numeric($value) || $value == "NOW()" || $value == "CURDATE()") {
            return $value;
        }
        elseif (is_null($value)) {
            return "NULL";
        }
        else {
            return "'".$this->db->real_escape_string($value)."'";
        }
    }
}