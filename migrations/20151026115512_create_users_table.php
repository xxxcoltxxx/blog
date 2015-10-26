<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $this->table("users")
            ->addColumn("email", "string", ['comment' => 'User email'])
            ->addColumn("password", "string", ['comment' => "md5 password hash"])
            ->addColumn("name", "string", ['comment' => 'User name'])
            ->addTimestamps()
            ->save();
    }
}
