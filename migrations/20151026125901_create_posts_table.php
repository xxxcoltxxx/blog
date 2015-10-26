<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
{
    public function change()
    {
        $this->table("posts")
            ->addColumn("title", "string", ['comment' => "Post title"])
            ->addColumn("content", "string", ['length' => MysqlAdapter::TEXT_MEDIUM, 'comment' => "post content"])
            ->addColumn("user_id", "integer", ['comment' => "User ID"])
            ->addTimestamps()
            ->addForeignKey("user_id", "users")
            ->save();
    }
}
