<?php

namespace PhpApi\Core\Helpers\Database;

trait Mapper
{

    /**
     * map records to models.
     * 
     * @param string $className the model name.
     * @param array $results the database records.
     * @return array returns array of objects.
     */
    public static function map($className, $results)
    {
        $obj = new $className();
        $listOfObjects = [];

        foreach ($results as $record) {
            $newObj = new $className();
            foreach ($record as $attr => $value) {

                /** mandatory attributes */
                if (in_array($attr, ['id', 'created_at', 'updated_at'])) {
                    $newObj->$attr = $value;
                    continue;
                }

                /** optional attributes */
                if (in_array($attr, $obj::$attributes)) {
                    $newObj->$attr = $value;
                }
            }
            array_push($listOfObjects, $newObj);
        }

        return $listOfObjects;
    }
}
