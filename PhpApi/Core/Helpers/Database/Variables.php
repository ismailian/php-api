<?php

namespace PhpApi\Core\Helpers\Database;

/**
 * this trait holds all the database class properties and members
 */
trait Variables
{
    /**
     * @var string $dbHostname The database hostname.
     */
    private $dbHostname;

    /**
     * @var string $dbUsername The database username. 
     */
    private $dbUsername;

    /**
     * @var string $dbPassword The database password.
     */
    private $dbPassword;

    /**
     * @var string $dbDatabase The database name.
     */
    private $dbDatabase;

    /**
     * @var mixed $dbResource The database resource. 
     */
    private $dbResource;

    /**
     * @var string $dbQuery The last stored query.
     */
    public $dbQuery;

    /**
     * @var array $keyContainer Contains keys.
     */
    private $_keys = [];

    /**
     * @var array $valueContainer Contains values.
     */
    private $_values = [];

    /**
     * @var string $_command a property holding the command name. 
     */
    private $_command;

    /**
     * @var string $_table a property holding the table name. 
     */
    private $_table;

    /**
     * @var array $_field a collection of fields to fetch when using SELECT command.
     */
    private $_fields = ['*'];

    /**
     * @var array $_set a collection of data in [set] clause.
     */
    private $_set = [];

    /**
     * @var array $_where a collection of data in [where] clause.
     */
    private $_where = [];

    /**
     * @var int $_limit a property indicating the limit value.
     */
    private $_limit = 0;

    /**
     * @var array $_orderBy a collection or data in [order by] clause. 
     */
    private $_orderBy = [];
}
