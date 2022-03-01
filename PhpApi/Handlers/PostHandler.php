<?php

namespace PhpApi\Handlers;

use PhpApi\Core\Helpers\Handler;
use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;
use PhpApi\Models\Wheel;

/**
 * the Post handler
 */
class PostHandler extends Handler
{

   /**
    * middlewares
    */
   public $middlewares = ['Authorization'];

   /**
    * index method
    * @param Request $req the current request
    * @param Response $res the current response
    */
   public function Index(Request $req, Response $res)
   {
      $wheels = Wheel::fetchAll();

      $res->status(200)->json([
         'status' => true,
         'data' => $wheels,
      ]);
   }
}
