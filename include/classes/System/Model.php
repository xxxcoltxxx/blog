<?php

namespace System;

use Data\Handlers\Validate;
use Data\Providers\DB;

class Model
{
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    public function setData(array $data = [])
    {
        Validate::check(is_array($data), "Wrong user data");
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function delete()
    {
        $db = DB::instance();
        $db->delete(static::$table, "id = '{$this->id}'");
    }
}