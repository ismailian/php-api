<?php

namespace PhpApi\Core\Adapters;

/**
 * The response adapter.
 * Provides core functionality for the Response class.
 */
class ResponseAdapter
{

    /**
     * @var string $status the response status code
     */
    public $status = 200;

    /**
     * @var array $headers the headers collection.
     */
    public $headers = [];

    /**
     * @var array $data the response data
     */
    public $data = ['status' => true, 'data' => ''];

    /**
     * prepare and send response
     */
    private function prepare()
    {
        /** set http status code */
        http_response_code($this->status);

        /** headers */
        foreach ($this->headers as $hkey => $hvalue)
            header("$hkey: $hvalue");
    }

    /**
     * the magic call method
     * @param string $name the function name.
     * @param array $args the function arguments.
     */
    public function __call($name, $args)
    {
        $headerName = ucwords(str_replace('_', '-', $name), '-');
        $this->headers[$headerName] = $args[0];

        return $this;
    }

    /**
     * set the http status code
     * @param int $code the http status code
     * @return object returns this object
     */
    public function status($code = 200)
    {
        $this->status = $code;
        return $this;
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

    /**
     * send file buffer to client.
     * 
     * @param string $filepath full location to the file to be sent.
     * @param string $filename the filename to be sent as.
     * @param bool $show_file_size whether to include the content-length header.
     * 
     */
    public function download($filepath, $filename = null, $show_file_size = true)
    {
        /** include file size if set to true */
        if ($show_file_size) {
            $this->headers['Content-Length'] = filesize($filepath);
        }

        /** set different filename if set */
        if ($filename) {
            $this->headers['Content-Disposition'] = 'attachment;filename=' . $filename;
        }

        /** for cache  */
        $this->headers['Expires'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
        $this->headers['Last-Modified'] = gmdate('D, d M Y H:i:s') . ' GMT';
        $this->headers['Pragma'] = 'no-cache';
        $this->headers['Content-Type'] = mime_content_type($filepath);

        /** prepare */
        $this->status(200)->prepare();

        /** intput/output streams */
        $input = @fopen($filepath, 'r');
        $output = @fopen('php://output', 'wb');

        /** send file */
        stream_copy_to_stream($input, $output);
        ob_flush();
        flush();
    }
}
