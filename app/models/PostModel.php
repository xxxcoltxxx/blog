<?php


use Data\Handlers\Validate;
use Data\Providers\DB;

class PostModel extends \System\Model
{
    public $id;
    public $title;
    public $content;
    public $user_id;
    public $comment_count;
    public $created_at;
    public $updated_at;

    private static $table = "posts";

    /** @var null|PostCommentModel[] */
    private $comments = null;

    /**
     * @param null $limit
     * @param null $offset
     * @return PostModel[]
     * @throws Exception
     */
    public static function getPosts($limit = null, $offset = null)
    {
        $db = DB::instance();
        $query = $db->getQuery();

        $query
            ->select("*")
            ->from(self::$table)
            ->order("created_at DESC")
            ->limit($limit, $offset);
        $result = $db->query($query);

        $posts = [];
        while ($post = $db->fetchRow($result)) {
            $posts[] = new self($post);
        }
        return $posts;
    }

    public static function get($id)
    {
        Validate::int($id, 1, null, "Пост не найден");
        $db = DB::instance();
        $query = $db->getQuery();
        $query
            ->select("*")
            ->from(self::$table)
            ->where("id = '%d'", $id);
        $post = $db->queryRow($query);

        Validate::check(!empty($post), "Пост не найден", 404);
        return new self($post);
    }

    public function save()
    {
        $data = [
            'id'            => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'user_id'       => $this->user_id,
            'comment_count' => $this->getCommentCount(),
            'updated_at'    => date(MYSQL_DATE_TIME)
        ];

        $db = DB::instance();
        $this->id = $db->store($data, self::$table);
        return $this->id;
    }

    /**
     * @return null|PostCommentModel[]
     */
    public function getComments()
    {
        if (is_null($this->comments)) {
            $this->comments = PostCommentModel::getComments($this);
            $this->comment_count = count($this->comments);
        }
        return $this->comments;
    }

    /**
     * Возвращает CommentCount
     * @return int
     */
    public function getCommentCount()
    {
        $this->comment_count = PostCommentModel::getCommentCount($this);
        return $this->comment_count;
    }
}