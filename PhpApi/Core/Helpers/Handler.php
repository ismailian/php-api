<?php

namespace PhpApi\Core\Helpers;

use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;

/**
 * the handler class
 */
class Handler
{

    /**
     * @var array $middlewares a property containing a list of middlewares to use with the handler.
     */
    public $middlewares = [];

    /**
     * default constructor
     */
    public function __construct()
    {
        array_map(function ($middleware) {
            $middlewareClass = 'PhpApi\\Middlewares\\' . $middleware;
            @call_user_func([new $middlewareClass(), 'intercept'], new Request, new Response);
        }, $this->middlewares);
    }
}
