<?php

namespace PhpApi\Core\Helpers\Database;

use PhpApi\Core\Config;
use PhpApi\Core\Functions\Database;

/**
 * Eloquent trait
 */
trait Eloquent
{

    /**
     * @var Databse $content database context
     */
    public static $context;

    /**
     * @var string $table the table name.
     */
    public static $table = '';

    /**
     * @var string $type the model class name
     */
    public static $type = '';

    /**
     * @var array $attributes attributes
     */
    public static $attributes = [];

    /**
     * 
     */
    public static function init()
    {
        $env = new Config();
        if (is_null(static::$context) || !static::$context) {
            static::$context = new Database(
                $env->database()->hostname,
                $env->database()->username,
                $env->database()->password,
                $env->database()->database
            );
        }
        return static::$context;
    }

    /**
     * get a single record
     */
    public static function fetch($fields = ['*'])
    {
        $records = static::init()->limit(1)->select(static::$table, $fields);
        $objects = static::map(static::$type, $records);

        return count($objects) > 0 ? $objects[0] : $objects;
    }

    /**
     * get all records
     */
    public static function fetchAll($fields = ['*'])
    {
        $records = static::init()->select(static::$table, $fields);
        return static::map(static::$type, $records);
    }

    /**
     * find records
     * 
     * @param array $conditions find records by given conditions.
     */
    public static function find(array $conditions, $fields = ['*'])
    {
        $records = static::init()->where($conditions)->select(static::$table, $fields);
        return static::map(static::$type, $records);
    }

    /**
     * find records by id
     * 
     * @param mixed $condition the condition with which to find a record.
     */
    public static function findOne($idOrCondition, $fields = ['*'])
    {
        $records = [];
        if (is_int($idOrCondition)) {
            $records = static::init()->where(['id' => $idOrCondition])->limit(1)->select(static::$table, $fields);
        } else {
            $records = static::init()->where($idOrCondition)->limit(1)->select(static::$table);
        }

        $objects = static::map(static::$type, $records);
        return count($objects) > 0 ? $objects[0] : $objects;
    }


    /**
     * saves this record to the database.
     * 
     * @return bool returns the status.
     */
    public function create()
    {
        $data = [];
        foreach (static::$attributes as $attr) {
            if (property_exists($this, $attr)) {
                $data[$attr] = $this->$attr;
            }
        }

        $this->id = static::init()->set($data)->insert(static::$table);
        return $this->id ?? false;
    }

    /**
     * updates this record.
     * 
     * @return bool returns the status.
     */
    public function save()
    {
        $data = [];
        foreach (static::$attributes as $attr) {
            if (property_exists($this, $attr)) {
                $data[$attr] = $this->$attr;
            }
        }

        return static::init()->set($data)->where(['id' => $this->id])->update(static::$table);
    }

    /**
     * deletes this record from the database.
     * 
     * @return bool returns the status.
     */
    public function delete()
    {
        return static::init()->where(['id' => $this->id])->delete(static::$table);
    }
}
