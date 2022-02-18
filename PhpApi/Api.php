<?php

namespace PhpApi;

use PhpApi\Core\Functions\Router;
use PhpApi\Core\Helpers\Cors;

/**
 * The Api App class
 */
class Api
{

    use Router;
    use Cors;

    /**
     * default constructor
     * @param Array $options the options to use with the app, containing ['prefix', 'cors'].
     */
    public function __construct($options = null)
    {
        if (!is_null($options)) {

            /** assign prefix */
            if (isset($options['prefix']))
                $this->prefix = $options['prefix'];

            /** assign cors */
            if (isset($options['Cors'])) {
                $this->cors = $options['Cors'];
            }
        }

        /** implement Cors for this api app */
        $this->cors($this->cors);
    }
}
