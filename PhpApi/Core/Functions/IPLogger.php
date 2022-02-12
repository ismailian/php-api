<?php

namespace PhpApi\Core\Functions;

/**
 * this is the ip logger class
 */
class IPLogger
{

    /**
     * default costructor
     */
    public function __construct()
    {
    }

    /**
     * get client public ip address.
     * @return string returns client's ip address
     */
    public function getIP()
    {
        /** get default ip */
        $ip = $_SERVER['REMOTE_ADDR']
            ?? $_SERVER['X-FORWARDED-FOR'];

        return $ip;
    }
}
