<?php

namespace PhpApi\Core\Functions;

use PhpApi\Core\Adapters\ResponseAdapter;

/**
 * The response class.
 * Once initialized it automatically prepares for a response to be delivered.
 */
class Response extends ResponseAdapter
{

    /**
     * default constructor
     * @param bool $auto_reset whether to clear data after sending response or not.
     */
    public function __construct()
    {
    }

    /**
     * redirect to a route.
     * @param string $route the route to redirect to.
     */
    public function redirect(string $route): void
    {
        /** set http status code */
        http_response_code(302);

        /** set location to route */
        header("Location: " . $route);
    }
}
