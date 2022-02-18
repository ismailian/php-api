<?php

namespace PhpApi\Core\Interfaces;

use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;

/**
 * The middleware interface.
 */
interface IMiddleware
{

    /**
     * apply middleware to the context request. 
     *
     * @param Request $req the context request. 
     * @param Response $res the context response. 
     */
    public function intercept(Request $req, Response $res);
}
