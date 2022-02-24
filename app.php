<?php


/*
|--------------------
| Composer autoload |
|--------------------
*/

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

/*
|-----------------------------
| Initialize a new API App   |
|-----------------------------
*/

use PhpApi\Api;

/** new Instance of Api App */
$ApiApp = new Api($options = [
    'prefix' => '/api/v1',
    'Cors' => [
        'origin'  => 'http://localhost',
        'methods' => ['*'],
        'headers' => ['*']
    ],
]);


/*
|-----------------------------
| Handle Routing             |
|-----------------------------
*/

$ApiApp->get('/', 'HomeHandler.Index');
$ApiApp->get('/posts', 'PostHandler.Index');
$ApiApp->get('/data', 'HomeHandler.Data');
