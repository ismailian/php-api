<?php

namespace PhpApi\Core\Interfaces;

/**
 * The middleware interface.
 */
interface IMiddleware
{
    /** implement the middleware. */
    public function use();
}
