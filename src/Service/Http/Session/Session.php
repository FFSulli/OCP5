<?php

declare(strict_types=1);

namespace App\Service\Http\Session;

final class Session
{
    private SessionParametersBag $sessionParamBag; // $_SESSION

    function __construct()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->sessionParamBag = new SessionParametersBag($_SESSION);
    }

    // PHP 8 version -> public function set(string $name, mixed $value): void
    /**
     * @param mixed $value
     */
    public function set(string $name, $value): void
    {
        $this->sessionParamBag->set($name, $value);
    }

    // PHP 8 version -> public function get(string $name): ?mixed
    /**
    * @return mixed
    */
    public function get(string $name)
    {
        return $this->sessionParamBag->get($name);
    }

    public function toArray(): ?array
    {
        return $this->sessionParamBag->all();
    }

    public function remove(string $name): void
    {
        $this->sessionParamBag->unset($name);
    }

    public function addFlashes(string $type, string $message): void
    {
        $flashes = $this->get('flashes');

        if ($flashes === null) {
            $flashes = [];
        }

        $flashes[$type] = $message;

        $this->set('flashes', $flashes);
    }

    public function getFlashes(): ?array
    {
        $flashes = $this->get('flashes');
        $this->remove('flashes');

        return $flashes;
    }
}
