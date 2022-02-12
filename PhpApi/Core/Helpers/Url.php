<?php

namespace PhpApi\Core\Helpers;

/**
 * The url helper.
 * Contains all the functionality needed to parse, extract and match routes to request urls.
 */
class Url
{

    /**
     * checks if a route is dynamic.
     * @param string $route the route to check.
     * @return bool returns true if route is dynamic else false.
     */
    public static function isDynamic($route): bool
    {
        @preg_match_all('/\/?(?<name>\:\w+[^\s\/])\/?/', $route, $result);
        return isset($result) && count($result['name']) > 0;
    }

    /**
     * extracts parameters' names from a dynamic route.
     * @param string $route the route to extract parameters from.
     * @return array returns an array with extracted parameters.
     */
    public static function getParams($route): array
    {
        // @preg_match_all('/\/?(?<name>\:\w+[^\s\/])\/?/', $route, $result);
        @preg_match_all('/\/?(?<name>\:[a-zA-Z0-9\_\@\.]+[^\s\/])\/?/', $route, $result);
        $params = [];
        foreach ($result['name'] as $param) {
            $params["/\\" . substr($param, 0) . "/"] = '(?<' . @substr($param, 1) . '>[^\s/]+)';
        }
        return $params;
    }

    /**
     * matches a url to a route.
     * It matches a url against a route and returns data based on what it finds.
     * @param string $url the url from received request.
     * @param string $route the route to compare to.
     * @return array returns an array containing [url, route, params, isMatch].
     */
    public static function getUrlInfo($url, $route)
    {
        /** get parameters */
        $params = Url::getParams($route);

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

        return [
            'url'     => $url,
            'route'   => $route,
            'params'  => $params,
            'isMatch' => isset($data['valid']),
        ];
    }
}
