<?php

namespace PhpApi\Core\Helpers\Database;

/**
 * the parent class Model
 */
class Model
{
    use Eloquent;
    use Mapper;

    public static $type = '';
    public static $table = '';
    public static $attributes = [];
}
