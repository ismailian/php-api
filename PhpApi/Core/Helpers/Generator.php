<?php

namespace PhpApi\Core\Helpers;

use Exception;

/**
 * The generator class.
 * generates random alpha/numeric or alpha Numeric strings
 */
class Generator
{

    /**
     * @var string $source The source to generator string from.
     */
    private $source = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var int $length The length of the string to generate.
     */
    private $length = 8;

    /**
     * default constructor
     * 
     * @param string $type the type of the generator.
     * @param int $length the length of the string.
     */
    public function __construct($type, $length)
    {
        if (!in_array($type, ['alpha', 'numeric', 'alphanumeric'])) {
            throw new Exception('Generator Type Undefined');
        }

        $this->type = $type;
        $this->length = $length;

        if ($type === 'alphanumeric') {
            $this->source .= '0123456789';
        }
    }

    /**
     * generates a random string from source in the given length.
     * @return string returns a randomly generated string. 
     */
    public function make(): string
    {
        $output = '';
        $isNumeric = $this->type === 'numeric';

        while (strlen($output) < $this->length) {
            $output .= $isNumeric
                ? rand(0, 9)
                : $this->source[rand(0, strlen($this->source))];
        }

        return $output;
    }

    /**
     * get a alphabets generator.
     * 
     * @param int $length the length of the string to generate.
     * @return Generator returns a generator object.
     */
    public static function alpha($length): Generator
    {
        return new Generator('alpha', $length);
    }

    /**
     * get a numeric generator.
     * 
     * @param int $length the length of the string to generate.
     * @return Generator returns a generator object.
     */
    public static function numeric($length): Generator
    {
        return new Generator('numeric', $length);
    }

    /**
     * get a alph+numeric generator.
     * 
     * @param int $length the length of the string to generate.
     * @return Generator returns a generator object.
     */
    public static function alphaNumeric($length): Generator
    {
        return new Generator('alphanumeric', $length);
    }
}
