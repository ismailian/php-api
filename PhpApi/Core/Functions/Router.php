<?php

namespace PhpApi\Core\Functions;

use Exception;
use PhpApi\Core\Helpers\Url;

/**
 * the router class
 */
trait Router
{

    use Url;

    /**
     * @var array $allowedMethods a list of allowed methods
     */
    public $allowedMethods = ['HEAD', 'GET', 'POST', 'PUT', 'DELETE'];

    /**
     * @var string $prefix the uri prefix.
     */
    public $prefix = '';

    /**
     * @var bool $handled a property indicating whether the route has been handled or not.
     * 
     */
    private $handled = false;

    /**
     * prepare a request and forward to the corresponding handler.
     * @param array $data the data containing info about route.
     */
    private function handle(array $data)
    {
        /** verify data */
        if (is_null($data) || empty($data))
            throw new Exception("Router Data Cannot Be Empty.");

        /** prepare request and response. */
        $request = new Request();
        $response = new Response();

        /** prepare variables */
        list($route, $handler) = array($data['uri'], $data['handler']);
        list($handlerClass, $handlerMethod) = explode('.', $handler);
        $urlInfo = $this->getUrlInfo($data['method'], $request->route->uri, $route);

        /** handler class */
        $handlerClass = 'PhpApi\\Handlers\\' . $handlerClass;

        /** verify if url matches the url */
        if ($urlInfo->isMatch) {
            $request->params = $urlInfo->params;

            /** verify Handler exists */
            if (!class_exists($handlerClass)) {
                throw new Exception('Handler Class Not Found.');
            }

            /** verify Method exists */
            if (!method_exists($handlerClass, $handlerMethod)) {
                throw new Exception('Handler Method Not Found.');
            }

            /** forward to handler */
            $this->handled = true;
            return @call_user_func([new $handlerClass(), $handlerMethod], $request, $response);
        }
    }

    /**
     * __call method allows us to call method names dynamically.
     * 
     * @param string $name the method name.
     * @param array $arguments the method args.
     */
    public function __call($name, $arguments)
    {
        list($methodName, $route, $handlerClass) = [strtoupper($name), $arguments[0], $arguments[1]];

        /** verify request method */
        if (!in_array($methodName, $this->allowedMethods)) {
            return (new Response())->status(405)->json([
                'status' => false,
                'message' => "method [$name] is not allowed on this resource."
            ]);
        }

        /** parse args */
        $this->handle([
            'method'  => $methodName,
            'uri'     => ($this->prefix . $route),
            'handler' => $handlerClass,
        ]);
    }

    /**
     * default destructor
     */
    public function __destruct()
    {
        if ($this->handled === false) {
            (new Response())->status(404)->json([
                'status' => false,
                'error' => 'route not found',
            ]);
        }
    }
}
