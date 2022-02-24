<?php

namespace PhpApi\Handlers;

use PhpApi\Core\Functions\Database;
use PhpApi\Core\Helpers\Handler;
use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;

/**
 * the home handler
 */
class HomeHandler extends Handler
{

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

    /**
     * 
     * @param Request $req the current request
     * @param Response $res the current response
     */
    public function Data(Request $req, Response $res)
    {
        $data = Database::instance()->limit(3)->select('wheels');
        $res->status(200)->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
