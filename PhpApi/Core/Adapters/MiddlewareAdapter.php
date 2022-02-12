<?php

namespace PhpApi\Core\Adapters;

use Exception;

/**
 * this is the Middleware Adapter
 */
class MiddlewareAdapter
{
    /**
     * intigrate a middleware with the given args
     * @param String $middlewareName the middleware class name.
     * @param Array $args the middleware class arguments.
     */
    public function addMiddleware(string $middlewareName, array $args): void
    {
        if (!class_exists('PhpApi\\Middlewares\\' . $middlewareName)) {
            throw new Exception('Middleware Does Not Exist.');
        }

        /** new instance of middleware */
        $middlewareName = 'PhpApi\\Middlewares\\' . $middlewareName;
        $middlewareObj = new $middlewareName($args);

        /** implement the middleware */
        $middlewareObj->use();
    }
}
