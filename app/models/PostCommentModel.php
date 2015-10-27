<?php

use Data\Providers\DB;

class PostCommentModel extends \System\Model
{
    public $id;
    public $post_id;
    public $user_id;
    public $name;
    public $message;
    public $created_at;
    public $updated_at;

    private static $table = "post_comments";

    /**
     * @param PostModel $post
     * @param null      $limit
     * @param null      $offset
     * @return PostCommentModel[]
     * @throws Exception
     */
    public static function getComments(PostModel $post, $limit = null, $offset = null)
    {
        $db = DB::instance();
        $query = $db->getQuery();
        $query
            ->select("*")
            ->from(self::$table)
            ->where("post_id = '%d'", $post->id)
            ->limit($limit, $offset);
        $result = $db->query($query);

        $comments = [];
        while ($comment = $db->fetchRow($result)) {
            $comments[] = new self($comment);
        }
        return $comments;
    }

    public function getDisplayName()
    {
        if ($this->user_id) {
            $user = UserModel::get($this->user_id);
            return $user->getDisplayName();
        } else {
            return $this->name;
        }
    }

    public static function getCommentCount(PostModel $post)
    {
        $db = DB::instance();
        $query = $db->getQuery();
        $query
            ->from(self::$table)
            ->where("post_id = '%d'", $post->id);
        return $db->queryRow($query->count_sql(), "count");
    }

    public function save()
    {
        $post = PostModel::get($this->post_id);

        $data = [
            'id'            => $this->id,
            'post_id'       => $this->post_id,
            'user_id'       => $this->user_id,
            'name'          => $this->name,
            'message'       => $this->message,
            'updated_at'    => date(MYSQL_DATE_TIME)
        ];

        $db = DB::instance();
        $this->id = $db->store($data, self::$table);

        $post->save();
        return $this->id;
    }
}