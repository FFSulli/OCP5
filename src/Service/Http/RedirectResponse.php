<?php

declare(strict_types=1);

namespace App\Service\Http;

final class RedirectResponse extends Response
{

    private $url;
    private int $statusCode;

    public function __construct($url, int $statusCode)
    {
        parent::__construct('', $statusCode, [
            'url' => $url
        ]);
        $this->url = $url;
        $this->statusCode = $statusCode;
    }
}
