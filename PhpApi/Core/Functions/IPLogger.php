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

    /**
     * get client origin
     * @return object returns object containing (hostname, ip_address, matches)
     */
    public static function origin()
    {
        return (object)[
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? $_SERVER['X-FORWARDED-FOR'],
            'hostname'   => $_SERVER['HTTP_ORIGIN'] ?? null,
            'matches'    => true
        ];
    }
}
