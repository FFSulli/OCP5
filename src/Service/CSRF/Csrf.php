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
    public function generateToken(): string
    {

        $this->session->set('csrfToken', bin2hex(openssl_random_pseudo_bytes(32)));

        return $this->session->get('csrfToken');
    }

    public function checkToken(string $token): bool
    {
        if ($token === $this->session->get('csrfToken')) {
            return true;
        }

        return false;
    }
}
