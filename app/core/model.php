<?php
namespace core;

use core\database;

class model
{
    protected function getSetting()
    {
        // get setting
        $setting['calledVars'] = get_class_vars(get_called_class());
        // get custom table name
        if (array_key_exists('table', $setting['calledVars'])) {
            $table = $setting['calledVars']['table'];
        } else {
            $table = substr_replace(get_called_class(), '', 0, 6);
        }
        $setting['table'] = $table;

        return $setting;
    }

    // create a record
    // @param array data
    // @return PDOStatement
    public function create(array $data)
    {
        // solve st
        $keys = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= '`'.$key.'`,';
            $values .= '\''.$value.'\',';
        }
        $keys = substr($keys, 0, -1);
        $values = substr($values, 0, -1);

        // connect database
        $db = new database;
        $c = $db->get();

        $setting = self::getSetting();

        $sql = "INSERT INTO `{$setting['table']}` ({$keys}) VALUES ({$values});";

        // run and return PDOStatement
        return $c->query($sql);
    }

    // all of these function could handle only one record
    // actually, I can let this function could delete more than one record
    // but for my laziness, I did not do so :p
    // @param array rule
    // @return PDOStatement
    public function delete(array $location)
    {
        // easy solve st
        $where = array_keys($location);

        // connect database
        $db = new database;
        $c = $db->get();

        $setting = self::getSetting();

        $sql = "DELETE FROM `{$setting['table']}` WHERE `{$where[0]}` = '{$location[$where[0]]}';";

        return $c->query($sql);
    }

    // update any data of a record
    // @param array locate a record
    // @param array data to update
    // @return PDOStatement
    public function update(array $location, array $data)
    {
        // solve st
        $where = array_keys($location);

        $handledData = '';
        foreach ($data as $key => $value) {
            $handledData .= '`'.$key.'` =  \''.$value.'\',';
        }
        $handledData = substr($handledData, 0, -1);

        $db = new database;
        $c = $db->get();

        $setting = self::getSetting();

        $sql = "UPDATE `{$setting['table']}` SET {$handledData} WHERE `{$where[0]}` = '{$location[$where[0]]}';";
        echo $sql;
        return $c->query($sql);
    }
}
