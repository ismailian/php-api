<?php

namespace PhpApi\Core\Helpers;

use PhpApi\Core\Functions\IPLogger;
use PhpApi\Core\Functions\Response;

/**
 * The Cors trait.
 * it is used to limit resource access to a specific origin
 */
trait Cors
{

    /**
     * @var array $headers allowed origin
     */
    private $origin = '*';

    /**
     * @var array $headers allowed methods
     */
    private $methods = ['*'];

    /**
     * @var array $headers allowed requests
     */
    private $headers = ['*'];

    /**
     * @var bool $strict whether to block all other origins or not
     */
    private $strict = false;

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

            if (isset($options['strict'])) # set strict behavior
                $this->strict = $options['strict'];
        }

        /** if strict */
        if ($this->strict) {
            if (!IPLogger::origin()->matches) {
                (new Response())->status(401)->json([
                    'status' => false,
                    'message' => 'you are not allowed to access this resource.'
                ]);
            }
        }

        header('Access-Control-Allow-Origin: '  . $this->origin);
        header('Access-Control-Allow-Methods: ' . implode(', ', $this->methods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->headers));
    }
}
