<?php

namespace PhpApi;

/** show errors only */
error_reporting(E_ERROR);

use PhpApi\Core\Adapters\RouteAdapter;
use PhpApi\Core\Functions\Router;

/**
 * The Api App class
 */
class Api extends RouteAdapter
{

    /**
     * default constructor
     * @param Array $options the options to use with the app, containing ['prefix', 'middlewares'].
     */
    public function __construct($options = null)
    {
        if (!is_null($options)) {

            /** assign prefix */
            if (isset($options['prefix']))
                $this->prefix = $options['prefix'];

            /** assign middlewares */
            if (isset($options['middlewares'])) {
                $this->middlewares = $options['middlewares'];
            }
        }

        /** initialize new instance of router */
        $this->router = new Router();

        /** use implemented middlewares */
        $this->use();
    }
}
