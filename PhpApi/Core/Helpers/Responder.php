<?php

namespace PhpApi\Core\Helpers;

trait Responder
{

    /**
     * @var string $status the response status code
     */
    private $status = 200;

    /**
     * @var array $headers the headers collection.
     */
    private $headers = [];

    /**
     * @var array $data the response data
     */
    private $data = ['status' => true, 'data' => ''];

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

    /**
     * redirect to a route.
     * @param string $route the route to redirect to.
     */
    public function redirect(string $route): void
    {
        /** set http status code */
        http_response_code(302);

        /** set location to route */
        header("Location: " . $route);
    }
}
