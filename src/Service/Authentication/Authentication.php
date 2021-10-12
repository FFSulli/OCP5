<?php

declare(strict_types=1);

namespace App\Service\Authentication;

use App\Model\Repository\UserRepository;
use App\Service\Http\Session\Session;

class Authentication
{
    private Session $session;
    private UserRepository $userRepository;

    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function authenticate(string $email)
    {
        $this->session->set('user', $email);
    }

    private function getAuthenticatedUser()
    {
        return $this->userRepository->findOneBy(['email' => $this->session->get('user')]);
    }

    public function isAuthenticated(): bool
    {
        if ($this->session->get('user')) {
            return true;
        }

        return false;
    }

    public function isAdmin()
    {
        if ($this->session->get('user')) {
            $user = $this->getAuthenticatedUser();

            if ($user->getRoleFk() == 3) {
                return true;
            }
        }

        return false;
    }

    public function isEditor()
    {
        if ($this->session->get('user')) {
            $user = $this->getAuthenticatedUser();

            if ($user->getRoleFk() == 2) {
                return true;
            }
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
