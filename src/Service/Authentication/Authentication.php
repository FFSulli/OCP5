<?php

declare(strict_types=1);

namespace App\Service\Authentication;

use App\Service\Http\Session\Session;

class Authentication
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function authenticate(string $email)
    {
        $this->session->set('user', $email);
    }

    public function isAuthenticated(): bool
    {
        if ($this->session->get('user')) {
            return true;
        }

        return false;
    }

    public function logout()
    {
        if ($this->session->get('user')) {
            $this->session->remove('user');
        }
    }
}