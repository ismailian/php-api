<?php

namespace PhpApi\Core\Helpers;

/**
 * The Cors trait.
 * it is used to limit resource access to a specific origin
 */
trait Cors
{

    /** allowed origin */
    private $origin = '*';

    /** allowed methods */
    private $methods = ['*'];

    /** allowed headers */
    private $headers = ['*'];

    /**
     * Set the cors for this resource.
     * 
     * @param array $options the cors properties. 
     */
    public function cors(array $options)
    {
        /** set options */
        if (is_array($options) && !empty($options)) {

            if (isset($options['origin'])) # set origin
                $this->origin = $options['origin'];

            if (isset($options['methods'])) # set allowed methods
                $this->methods = $options['methods'];

            if (isset($options['headers'])) # set allowed headers
                $this->headers = $options['headers'];
        }

        header('Access-Control-Allow-Origin: '  . $this->origin);
        header('Access-Control-Allow-Methods: ' . implode(', ', $this->methods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->headers));
    }
}
