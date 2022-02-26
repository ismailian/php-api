<?php

namespace PhpApi\Models;

use PhpApi\Core\Helpers\Database\Model;

/**
 * Wheel model
 */
class Wheel extends Model
{

    public static $type = Wheel::class;
    public static $table = 'wheels';
    public static $attributes = ['type_id', 'wheel_number'];
}
