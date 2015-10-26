<?php

use Phinx\Migration\AbstractMigration;

class CreatePostCommentsTable extends AbstractMigration
{
    public function change()
    {
        $this->table("post_comments")
            ->addColumn("post_id", "integer", ['comment' => "Post ID"])
            ->addColumn("user_id", "integer", ['null' => true, 'comment' => "User ID"])
            ->addColumn("name", "string", ['null' => true, 'comment' => "User name for anonym"])
            ->addColumn("message", "string", ['length' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM, 'comment' => "Comment message"])
            ->addTimestamps()
            ->addForeignKey("user_id", "users")
            ->addForeignKey("post_id", "posts")
            ->save();
    }
}
