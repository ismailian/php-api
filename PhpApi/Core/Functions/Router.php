<?php

namespace PhpApi\Core\Functions;

use Exception;
use PhpApi\Core\Helpers\Url;

/**
 * the router class
 */
class Router
{

    /**
     * @var bool $handled a property indicating whether the route has been handled or not.
     * 
     */
    private $handled = false;

    /**
     * default constructor
     */
    function __construct()
    {
    }

    /**
     * prepare a request and forward to the corresponding handler.
     * @param array $data the data containing info about route.
     */
    public function handle(array $data)
    {
        /** verify data */
        if (is_null($data) || empty($data))
            throw new Exception("Router Data Cannot Be Empty.");

        /**
         * prepare request and response.
         */
        $request = new Request();
        $response = new Response();

        /**
         * prepare variables
         */
        list($method, $route, $handler) = array($data['method'], $data['uri'], $data['handler']);
        $urlInfo = Url::getUrlInfo($request->route->uri, $route);
        list($isMatch, $params) = [$urlInfo['isMatch'], $urlInfo['params']];
        list($handlerClass, $handlerMethod) = explode('.', $handler);

        /** handler class */
        $handlerClass = 'PhpApi\\Handlers\\' . $handlerClass;

        /** verify method */
        if ($method === $request->method) {

            /** verify if url matches the url */
            if ($isMatch) {
                $request->params = (object)$params;

                /** verify Handler exists */
                if (!class_exists($handlerClass)) {
                    throw new Exception('Handler Class Not Found.');
                }

                /** verify Method exists */
                if (!method_exists(new $handlerClass(), $handlerMethod)) {
                    throw new Exception('Handler Method Not Found.');
                }

                /** forward to handler */
                @call_user_func([new $handlerClass(), $handlerMethod], $request, $response);
                $this->handled = true;
            }
        }
    }

    /**
     * handle a request and return callback.
     * @param array $ctx the context to handle.
     * @param callback $callback the callback to call.
     */
    public function callback(array $ctx, $callback)
    {
        /** verify context */
        if (!is_array($ctx)) {
            throw new Exception('Context Is Not Valid');
        }

        /** prevent double route handling */
        if ($this->handled) {
            return;
        }

        /**
         * prepare request and response
         */
        $request = new Request();
        $response = new Response();

        /** extract info from context and prepare url match data */
        list($method, $route) = [$ctx['method'], $ctx['uri']];
        $urlInfo = Url::getUrlInfo($request->route->uri, $route);
        list($isMatch, $params) = [$urlInfo['isMatch'], $urlInfo['params']];

        /** set params to request */
        $request->params = (object)$params;

        /** all methods */
        if ($method === '*' && $isMatch) {
            $callback($request, $response);
            $this->handled = true;
        }

        /** handle specific method */
        if ($method !== '*' && $request->method === $method) {
            if ($isMatch) {
                $callback($request, $response);
                $this->handled = true;
            }
        }
    }

    /**
     * default destructor
     */
    public function __destruct()
    {
        if (!$this->handled) {
            return (new Response())->status(404)->json([
                'status' => false,
                'error' => 'route not found',
            ]);
        }
    }
}
