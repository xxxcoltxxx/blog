<?php

use Phinx\Migration\AbstractMigration;

class AddPostCommentsCounterCacheField extends AbstractMigration
{
    public function change()
    {
        $this->table("posts")
            ->addColumn("comment_count", "integer", ['after' => "user_id", 'null' => true, 'comment' => "Поле для кеширования кол-ва лайков"])
            ->save();
    }
}
