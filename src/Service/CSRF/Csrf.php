<?php

declare(strict_types=1);

namespace App\Service\CSRF;

use App\Service\Http\Session\Session;
use Exception;

class Csrf
{

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @throws Exception
     */
    public function generateToken(): void
    {
        $this->session->set('csrf_token', bin2hex(random_bytes(20)));
    }

    public function getToken(): string
    {
        return $this->session->get('csrf_token');
    }

    public function deleteToken(): void
    {
        $this->session->remove('csrf_token');
    }
}
