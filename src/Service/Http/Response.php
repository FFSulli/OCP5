<?php

declare(strict_types=1);

namespace App\Service\Http;

class Response
{
    private string $content;
    private int $status;
    private array $headers;

    function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function send(): void
    {
        switch ($this->status) {
            case 200:
                header("HTTP/1.0 200 OK");
                break;
            case 201:
                header("HTTP/1.0 201 Created");
                break;
            case 202:
                header("HTTP/1.0 202 Accepted");
                break;
            case 300:
                header("HTTP/1.0 300 Multiple Choices");
                break;
            case 301:
                header("HTTP/1.0 301 Moved Permanently");
                break;
            case 302:
                header("HTTP/1.0 302 Found");
                break;
            case 400:
                header("HTTP/1.0 302 Bad Request");
                break;
            case 401:
                header("HTTP/1.0 401 Unauthorized");
                break;
            case 403:
                header("HTTP/1.0 403 Forbidden");
                break;
            case 404:
                header("HTTP/1.0 404 Not Found");
                break;
            case 500:
                header("HTTP/1.0 500 Internal Server Error");
                break;
            default:
                exit("Unknown HTTP status code : " . $this->status);
        }

        if ($this->headers['url']) {
            header('Location: ' . $this->headers['url'], true, $this->status);
            exit();
        }

        echo $this->content;
    }
}
