<?php

namespace PhpApi\Core\Helpers\Database;

/**
 * this trait is responsible for generating top level database commands:
 */
trait Command
{

    /**
     * Select fields from a table
     * 
     * @param array $fields The fields to select.
     * @return object returns this instance of database.
     */
    public function select(string $table, array $fields = ['*'])
    {
        $this->_table = $table;
        $this->_command = 'SELECT';

        /** add [id] to the fields if not there and if fields is not set to global(*) */
        if (!in_array('*', $fields)) {
            if (!in_array('id', $fields)) {
                array_push($fields, 'id');
            }
        }

        $this->_fields = $fields;
        return $this->query();
    }

    /**
     * Insert new resource into the database.
     * @param String $table The table to insert the resource into.
     * @param Array $keyValuePair The values to be inserted.
     * @return object returns this instance of database.
     */
    public function insert(String $table)
    {
        $this->_table = $table;
        $this->_command = 'INSERT';
        return $this->query();
    }

    /**
     * updates resource(s) on a table.
     * 
     * @param string $table the table to update from.
     * @return object returns this instance of database.
     */
    public function update(string $table)
    {
        $this->_table = $table;
        $this->_command = 'UPDATE';
        return $this->query();
    }

    /**
     * deletes a resource(s) from database.
     * 
     * @param string $table The table to delete from.
     * @return object returns this instance of database.
     */
    public function delete(String $table)
    {
        $this->_table = $table;
        $this->_command = 'DELETE';
        return $this->query();
    }

    /**
     * counts records on a table.
     * 
     * @return int return number of records on a table.
     */
    public function count(string $table): int
    {
        $result = $this->raw("SELECT count(*) FROM `$table`");
        return $result->fetch_array();
    }

    /**
     * truncates a table.
     * 
     * @param string table name to drop.
     * @return bool result of the query.
     */
    public function truncate(string $table): bool
    {
        $this->raw("TRUNCATE `$table`");
        return true;
    }
}
