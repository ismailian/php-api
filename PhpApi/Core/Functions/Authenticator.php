<?php

namespace PhpApi\Core\Functions;

use Exception;
use PhpApi\Core\Config;
use PhpApi\Core\Helpers\Generator;

/**
 * the authentication class
 */
class Authenticator
{

    /**
     * default constructor
     */
    public function __construct()
    {
        $env = new Config();
        list($this->length, $this->pattern) = [$env->token()->length, $env->token()->pattern];
        $this->pattern = str_replace('{n}', $this->length, $this->pattern);
    }

    /**
     * checks if a token is still valid.
     * @return bool returns true if valid else false.
     */
    public function check(Request $req): bool
    {
        if (!$this->getToken($req)) {
            return false;
        }

        return true;
    }

    /**
     * parse the token submited in the authorization header.
     * 
     * @param Request $req the current request context.
     * @return string|null returns token is available or null. 
     */
    public function getToken(Request $req)
    {
        $authHeader = $req->headers->authorization;
        if (!$authHeader) return null;

        @preg_match("/[Tt]oken (?<token>$this->pattern)/", $authHeader, $result);
        if (!isset($result['token'])) {
            throw new Exception('Token Is Invalid');
        }

        return $result['token'];
    }

    /**
     * generates a new token
     * 
     * @return string returns a newly generated token
     */
    public function makeToken(): string
    {
        return Generator::alphaNumeric($this->length)->make();
    }
}
