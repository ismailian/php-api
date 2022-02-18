<?php

namespace PhpApi\Core\Functions;

use PhpApi\Core\Helpers\Responder;

/**
 * The response class.
 * Once initialized it automatically prepares for a response to be delivered.
 */
class Response
{

    use Responder;

    /**
     * default constructor
     * @param bool $auto_reset whether to clear data after sending response or not.
     */
    public function __construct()
    {
    }

    /**
     * send response to the client.
     * 
     * @param string $contentType the mime type of the response resource.
     * @param mixed $data the content data to be sent.
     */
    public function send($data)
    {
        $this->prepare();
        echo $data;
    }

    /**
     * respond with json data.
     * 
     * @param mixed $data the data to be sent back to the client.
     */
    public function json($data)
    {
        $this->headers['Content-Type'] = 'application/json';
        $this->prepare();

        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * respond with text data
     * @param mixed $data the data to be sent back to the client.
     */
    public function text($data)
    {
        $this->headers['Content-Type'] = 'text/plain';
        $this->prepare();

        /** send */
        echo $this->data;
    }

    /**
     * respond with html data
     * @param mixed $data the data to be sent back to the client.
     */
    public function html($data)
    {
        $this->headers['Content-Type'] = 'text/html';
        $this->prepare();

        /** send */
        echo $this->data;
    }
}
