<?php

namespace PhpApi\Core\Helpers;

use PhpApi\Core\Functions\Request;

/**
 * The url helper.
 * Contains all the functionality needed to parse, extract and match routes to request urls.
 */
trait Url
{

    /**
     * extracts parameters' names from a dynamic route.
     * @param string $route the route to extract parameters from.
     * @return array returns an array with extracted parameters.
     */
    private function getParams($route): array
    {
        @preg_match_all('/\/?(?<name>\:[^\s\-\/]+)\/?/', $route, $result);
        $params = [];
        foreach ($result['name'] as $param) {
            $params["/\\" . substr($param, 0) . "/"] = '(?<' . @substr($param, 1) . '>[^\s/]+)';
        }
        return $params;
    }

    /**
     * matches a url to a route.
     * It matches a url against a route and returns data based on what it finds.
     * @param string $requestMethod the request method.
     * @param string $url the url from received request.
     * @param string $route the route to compare to.
     * @return object returns an array containing [url, route, params, isMatch].
     */
    public function getUrlInfo($requestMethod, $url, $route): object
    {
        /** get parameters */
        $params = $this->getParams($route);

        /** make the trailing slash optional */
        list($newRoute, $routeLength, $lastSlashPos) = [$route, strlen($route), strrpos($route, '/')];
        if ($routeLength - 1 === $lastSlashPos) $newRoute .= '?';
        else $newRoute .= '/?';

        $regex = @preg_replace(array_keys($params), array_values($params), $newRoute);
        $regex = @str_replace('/', '\/', $regex);
        @preg_match("/^(?<valid>$regex)$/", $url, $data);

        $params = array_filter($data ?? [], function ($key) {
            return !is_numeric($key) && $key !== 'valid';
        }, ARRAY_FILTER_USE_KEY);

        /** verify request method */
        $method = (new Request())->method;

        return (object)[
            'url'     => $url,
            'route'   => $route,
            'params'  => $params,
            'isMatch' => isset($data['valid']) && ($method === $requestMethod),
        ];
    }
}
