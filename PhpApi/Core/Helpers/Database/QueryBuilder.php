<?php

namespace PhpApi\Core\Helpers\Database;

use PDO;

/**
 * this trait is responsible for building database query.
 */
trait QueryBuilder
{

    /**
     * cleans up variables.
     */
    private function empty()
    {
        $this->dbQuery = '';
        $this->_command = '';
        $this->_table = '';
        $this->_limit = 0;
        $this->_keys = [];
        $this->_values = [];
        $this->_fields = [];
        $this->_set = [];
        $this->_where = [];
    }

    /**
     * get value type.
     * 
     * @param mixed $value the value to get the type of.
     */
    private function getType($value)
    {
        $type = PDO::PARAM_STR;
        switch (true) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
        return $type;
    }

    /**
     * builds the query before submitting it to the database.
     * 
     * @return object|bool returns a prepared sql statement or false.
     */
    private function build()
    {
        /** sql query */
        $sql = "";

        /** add command */
        $sql .= $this->_command;

        /** add command auxiliary */
        switch ($this->_command) {
            case 'SELECT':
                $sql .= ' ' . join(',', $this->_fields) . ' FROM ';
                break;
            case 'INSERT':
                $sql .= ' INTO ';
                break;
            case 'UPDATE':
                $sql .= ' ';
                break;
            case 'DELETE':
                $sql .= ' FROM ';
                break;
        }

        /** add table name */
        $sql .= "`{$this->_table}`";

        /** if update/delete/select check for where clause. */
        if (in_array($this->_command, ['SELECT', 'UPDATE', 'DELETE'])) {

            /**
             * accept update/delete
             */
            if (($this->_command !== 'SELECT') && count($this->_set) > 0) {

                $keys = array_keys($this->_set);
                $values = array_values($this->_set);

                $raw = [];
                foreach ($this->_set as $key => $value) {
                    array_push($raw, "`$key`=:" . $key);
                }

                $sql .= ' SET ' . join(',', $raw);
                array_push($this->_keys, ...$keys);
                array_push($this->_values, ...$values);
            }

            /**
             * accept select/delete/update
             */
            if (count($this->_where) > 0) {

                $keys = array_keys($this->_where);
                $values = array_values($this->_where);

                $sql .= ' WHERE ';

                $raw = [];
                foreach ($this->_where as $key => $value) {
                    array_push($raw, "`$key`=:" . $key);
                }

                $sql .= join(' AND ', $raw);
                array_push($this->_keys, ...$keys);
                array_push($this->_values, ...$values);
            }
        }

        /**
         * if insert check for set clause.
         */
        if ($this->_command === 'INSERT') {
            if (count($this->_set) > 0) {

                $keys = array_keys($this->_set);
                $values = array_values($this->_set);

                $sql .= " (" . implode(', ', $keys) . ") ";

                /** convert values to '?' for the prepare statment */
                for ($i = 0; $i < count($values); $i++)
                    $values[$i] = ":" . $keys[$i];

                $sql .= "VALUES (" . implode(', ', $values) . ")";
                array_push($this->_keys, ...$keys);
                array_push($this->_values, ...array_values($this->_set));
            }
        }

        /**
         * order_by and limit only apply to select command
         */
        if ($this->_command === 'SELECT') {

            /** order by statement */
            if (count($this->_orderBy) > 0) {

                $field = $this->_orderBy['field'];
                $value = $this->_orderBy['value'];

                $sql .= " ORDER BY `$field` $value";
            }

            /** limit clause */
            if ($this->_limit > 0) {
                $sql .= ' LIMIT ' . $this->_limit;
            }
        }

        /**
         * preparing statement 
         */
        $this->dbQuery = $sql;
        $bindableParams = count($this->_keys) > 0 && count($this->_values);

        if ($bindableParams) {

            /** prepare query */
            $dbStatement = $this->dbResource->prepare($this->dbQuery);

            /** check if successfully prepared */
            if ($dbStatement !== false) {
                for ($i = 0; $i < count($this->_keys); $i++) {

                    $key = $this->_keys[$i];
                    $value = $this->_values[$i];

                    /** bind key/value */
                    $dbStatement->bindValue(':' . $key, $value, $this->getType($value));
                }
                return $dbStatement;
            }
        }
        return false;
    }

    /**
     * submits a query to the database.
     * 
     * @param string $query The query to submit.
     */
    public function query(string $query = null)
    {
        /** build the query */
        $dbStatement = $this->build();

        /** no parameters to be prepared and executed */
        if ($dbStatement === false) {
            $payload = $this->dbResource->query($this->dbQuery);
            return (is_object($payload) ? $payload->fetchAll() : $payload);
        }

        /** execute */
        $dbStatement->execute();

        // /** return status */
        if (is_bool($dbStatement))
            return $dbStatement;

        /** available data */
        if (is_object($dbStatement)) {

            /** capture error if any */
            $this->_error = $dbStatement->errorInfo ?? null;

            /** return id if command == insert */
            if ($this->_command === 'INSERT')
                return $this->dbResource->lastInsertId();

            /** return status */
            if (in_array($this->_command, ['UPDATE', 'DELETE']))
                return ($dbStatement->rowCount() > 0);

            /** return results */
            else
                return $dbStatement->fetchAll();
        }
        return false;
    }

    /**
     * Run a query directly and return result.
     */
    public function raw(string $query)
    {
        return $this->dbResource->query($query);
    }
}
