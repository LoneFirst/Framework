<?php
namespace core;

use core\database;

class model
{
    protected static function getSetting()
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
    public static function create(array $data)
    {
        $c = database::get();

        // solve st
        $keys = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= '`'.$key.'`,';
            $values .= $c->quote($value).',';
        }
        $keys = substr($keys, 0, -1);
        $values = substr($values, 0, -1);

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
    public static function delete(array $location)
    {
        $c = database::get();

        // solve st
        $where = array_keys($location);

        $where = '';
        foreach ($location as $key => $value) {
            $where .= '`'.$key.'` = '.$c->quote($value).' AND ';
        }
        $where = substr($where, 0, -5);

        $setting = self::getSetting();

        $sql = "DELETE FROM `{$setting['table']}` WHERE {$where};";

        return $c->query($sql);
    }

    // update any data of a record
    // @param array locate a record
    // @param array data to update
    // @return PDOStatement
    public static function update(array $location, array $data)
    {
        $c = database::get();

        // solve st
        $where = array_keys($location);

        $where = '';
        foreach ($location as $key => $value) {
            $where .= '`'.$key.'` = '.$c->quote($value).' AND ';
        }
        $where = substr($where, 0, -5);

        $handledData = '';
        foreach ($data as $key => $value) {
            $handledData .= '`'.$key.'` =  '.$c->quote($value).',';
        }
        $handledData = substr($handledData, 0, -1);

        $setting = self::getSetting();

        $sql = "UPDATE `{$setting['table']}` SET {$handledData} WHERE {$where};";
        return $c->query($sql);
    }

    // select one row of data
    // @param array $column column of needed
    // @param array $location rule
    // @return row data
    public static function select(array $column, array $location)
    {
        $c = database::get();

        $handledColumn = '';
        foreach ($column as $key => $value) {
            $handledColumn .= '`'.$value.'`,';
        }
        $handledColumn = substr($handledColumn, 0, -1);

        $where = '';
        foreach ($location as $key => $value) {
            $where .= '`'.$key.'` = '.$c->quote($value).' AND ';
        }
        $where = substr($where, 0, -5);

        $setting = self::getSetting();

        $sql = "SELECT {$handledColumn} FROM {$setting['table']} WHERE {$where};";
        return $c->query($sql)->fetch();
    }

    // execute original sql and return the PDOStatement
    // however, this function is not recommended
    // @param string sql
    // @return PDOStatement
    public static function query(string $sql)
    {
        $c = database::get();
        return $c->query($sql);
    }
}
