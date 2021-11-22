<?php

namespace App\lib\http;

class Session implements SessionInterface
{

    public function __construct()
    {
        session_start();
    }

    /**
     * @return mixed
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function set(string $key, $value): SessionInterface
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function clear(): void
    {
        session_unset();
    }

    /**
     * @param mixed $id
     */
    private function setId(string $id): void
    {
        $this->id = $id;
    }
}
