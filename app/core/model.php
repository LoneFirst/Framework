<?php
namespace core;

use core\database;

class model
{
    public function create($data)
    {
        $keys = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= '`'.$key.'`,';
            $values .= '\''.$value.'\',';
        }
        $keys = substr($keys, 0, -1);
        $values = substr($values, 0, -1);

        $db = new database;
        $c = $db->get();

        $calledVars = get_class_vars(get_called_class());
        if (array_key_exists('table', $calledVars)) {
            $table = $calledVars['table'];
        } else {
            $table = substr_replace(get_called_class(), '', 0, 6);
        }
        $sql = "INSERT INTO `{$table}` ({$keys}) VALUES ({$values});";
        echo $sql;
        $c->query($sql);
    }
}
