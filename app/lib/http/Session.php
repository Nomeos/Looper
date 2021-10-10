<?php

class Session
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    private function setId(string $id): void
    {
        $this->id = $id;
    }
}
