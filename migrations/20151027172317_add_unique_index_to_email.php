<?php

use Phinx\Migration\AbstractMigration;

class AddUniqueIndexToEmail extends AbstractMigration
{
    public function change()
    {
        $this->table("users")
            ->addIndex("email", ['unique' => true])
            ->save();
    }
}
