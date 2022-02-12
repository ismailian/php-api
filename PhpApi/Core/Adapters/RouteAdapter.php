<?php

namespace PhpApi\Core\Adapters;


/**
 * this is the route adapter
 */
class RouteAdapter extends MiddlewareAdapter
{

    /**
     * @var Router $router the context router.
     */
    public $router;

    /**
     * @var string $route the context route.
     */
    public $route;

    /**
     * @var string $prefix the uri prefix.
     */
    public $prefix = '';

    /**
     * @var array $middlewares the middlewares to use with http requests.
     */
    public $middlewares = array();

    /**
     * default constructor
     */
    public function __construct()
    {
    }

    /**
     * map a request to the corresponding handler based on route
     * 
     * @param String $route the route to handle.
     * @param String $handler the handler class. 
     */
    public function get(String $route, String $handlerClass): void
    {
        $this->router->handle([
            'method'  => 'GET',
            'uri'     => ($this->prefix . $route),
            'handler' => $handlerClass,
        ]);
    }

    /**
     * map a request to the corresponding handler based on route
     * 
     * @param String $route the route to handle.
     * @param String $handler the handler class. 
     */
    public function post(String $route, String $handlerClass): void
    {
        $this->router->handle([
            'method'  => 'POST',
            'uri'     => ($this->prefix . $route),
            'handler' => $handlerClass,
        ]);
    }

    /**
     * map a request to the corresponding handler based on route
     * 
     * @param String $route the route to handle.
     * @param String $handler the handler class. 
     */
    public function head(String $route, String $handlerClass): void
    {
        $this->router->handle([
            'method'  => 'HEAD',
            'uri'     => ($this->prefix . $route),
            'handler' => $handlerClass,
        ]);
    }

    /**
     * map a request to the corresponding handler based on route
     * 
     * @param String $route the route to handle.
     * @param String $handler the handler class. 
     */
    public function put(String $route, String $handlerClass): void
    {
        $this->router->handle([
            'method'  => 'PUT',
            'uri'     => ($this->prefix . $route),
            'handler' => $handlerClass,
        ]);
    }

    /**
     * map a request to the corresponding handler based on route
     * 
     * @param string $route the route to handle.
     * @param string $handler the handler class. 
     */
    public function delete(string $route, string $handlerClass): void
    {
        $this->router->handle([
            'method'  => 'DELETE',
            'uri'     => ($this->prefix . $route),
            'handler' => $handlerClass,
        ]);
    }

    /**
     * handle a request
     * 
     * @param string $route the route to handle.
     * @param mixed $callback the call back to handle request, response.
     */
    public function handle(String $route, $callback): void
    {
        $this->router->callback([
            'method'   => '*',
            'uri'      => ($this->prefix . $route),
        ], $callback);
    }

    /**
     * use provided middleware(s)
     * 
     * @param Array $middlewares the middleware(s) to use globally with all requests.
     */
    public function use($middlewares = null): void
    {
        $this->middlewares = $middlewares ?? [];

        /** implement all middlewares in collection */
        foreach ($this->middlewares as $middleware => $args) {
            $this->addMiddleware($middleware, $args);
        }
    }
}
