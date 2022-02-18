<?php

namespace PhpApi\Handlers;

use PhpApi\Core\Helpers\Handler;
use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;
use PhpApi\Middlewares\Authorization;
use tidy;

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
      $res->status(200)->json([
         'status' => true,
         'data' => [
            'posts' => [
               [
                  'id' => 1,
                  'title' => 'Welcome',
                  'content' => 'This is the first post.',
               ],
               [
                  'id' => 2,
                  'title' => 'Second Post',
                  'content' => 'This is the second post.',
                  'attachments' => [
                     'image;http://cdn.flexcdn.me/@john.doe/782619273JdqVwgHat2S/images/1223b8c30a347321299611f873b449ad.jpg',
                     'video;http://cdn.flexcdn.me/@john.doe/782619273JdqVwgHat2S/videos/0c821f675f132d790b3f25e79da739a7.mp4'
                  ]
               ]
            ],
         ]
      ]);
   }
}
