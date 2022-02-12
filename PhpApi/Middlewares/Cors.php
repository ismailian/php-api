<?php

namespace PhpApi\Middlewares;

use PhpApi\Core\Interfaces\IMiddleware;

/**
 * The Cors middleware. 
 */
class Cors implements IMiddleware
{

    /** allowed origin */
    private $origin = 'localhost';

    /** allowed methods */
    private $methods = ['*'];

    /** allowed headers */
    private $headers = ['*'];

    /**
     * default constructor
     * @param Array $options An associative array containing all options ['origin', 'methods', 'headers'] 
     */
    public function __construct(array $options)
    {
        if (is_array($options) && !empty($options)) {

            if (isset($options['origin'])) # set origin
                $this->origin = $options['origin'];

            if (isset($options['methods'])) # set allowed methods
                $this->methods = $options['methods'];

            if (isset($options['headers'])) # set allowed headers
                $this->headers = $options['headers'];
        }
    }

    /**
     * The origin to be allowed to access this resource.
     * @param String $origin the origin to allow access.
     * @return object returns this object.
     */
    public function set_origin(String $origin): object
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * the methods allowed for this resource.
     * @param Array $methods the methods to allow.
     * @return object returns this object.
     */
    public function set_methods(array $methods): object
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * the methods headers for this resource.
     * @param Array $headers the headers to allow.
     * @return object returns this object.
     */
    public function set_headers(array $headers): object
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Set the cors for this resource and allow for consumption.
     */
    public function use()
    {
        /** set origin */
        header('Access-Control-Allow-Origin: ' . $this->origin);

        /** set methods */
        header('Access-Control-Allow-Methods: ' . implode(', ', $this->methods));

        /** set headers */
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->headers));
    }
}
