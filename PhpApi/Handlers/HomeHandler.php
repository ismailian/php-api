<?php

namespace PhpApi\Handlers;

use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;

/**
 * the home handler
 */
class HomeHandler
{

    /**
     * default constructor
     */
    public function __construct()
    {
    }

    /**
     * index method
     * @param Request $req the current request
     * @param Response $res the current response
     */
    public function Index(Request $req, Response $res)
    {
        $res->status(200)->json([
            'status' => true,
            'data' => [
                'route' => $req->route,
            ]
        ]);
    }
}
