<?php

namespace PhpApi\Middlewares;

use PhpApi\Core\Functions\Authenticator;
use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;
use PhpApi\Core\Helpers\Middleware;

/**
 * The middleware class.
 * monitors the authentication flow through the authorization header.
 */
class Authorization extends Middleware
{

    /**
     * apply middleware to the context request. 
     *
     * @param Request $req the content request. 
     */
    public function intercept(Request $req, Response $res)
    {
        $auth = new Authenticator();
        $this->allowed = $auth->check($req);
    }
}
