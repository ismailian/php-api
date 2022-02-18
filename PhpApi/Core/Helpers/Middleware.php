<?php

namespace PhpApi\Core\Helpers;

use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;
use PhpApi\Core\Interfaces\IMiddleware;

/**
 * The middleware class.
 * monitors the authentication flow through the authorization header.
 */
class Middleware implements IMiddleware
{

    /**
     * @var bool $allowed a property indicating whether request should be allowed or blocked.
     */
    public $allowed = true;

    /**
     * @var string the message to response with in case the request is blocked.
     */
    public $message = 'You are not authorized to use this resource.';

    /**
     * apply middleware to the context request. 
     *
     * @param Request $req the content request. 
     */
    public function intercept(Request $req, Response $res)
    {
    }

    /**
     * default destructor
     */
    function __destruct()
    {
        if (!$this->allowed) {
            (new Response)->status(401)->json([
                'status' => false,
                'message' => $this->message,
            ]);
            die();
        }
    }
}
