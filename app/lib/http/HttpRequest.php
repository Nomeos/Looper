<?php

namespace App\lib\http;

use App\lib\http\Session;
use App\lib\http\SessionFactory;
use Exception;

class HttpRequest
{
    private array $headers;

    private string $method;

    // php session
    private Session $session;

    private array $bodyData;

    // http version
    private float $protocolVersion;

    private string $uri;

    public function __construct()
    {
        // http headers (in $_SERVER) start with a
        // key name like HTTP_
        $this->setHeaders(
            array_filter($_SERVER, function ($key) {
                return strpos($key, 'HTTP_') === 0;
            }, ARRAY_FILTER_USE_KEY)
        );

        $this->setSession(SessionFactory::fromCookie($_COOKIE));

        $this->setUri($_SERVER["REQUEST_URI"]);
        $this->setMethod($_SERVER["REQUEST_METHOD"]);

        $this->fetchProtocolVersion();
        $this->fetchBodyData();
    }

    /**
     * @param mixed $headers
     */
    private function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param Session $session
     */
    private function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * @param string $uri
     */
    private function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    private function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     *
     */
    private function fetchProtocolVersion()
    {
        $http_version = $_SERVER["SERVER_PROTOCOL"];
        $result = explode('/', $http_version);

        if (count($result)) {
            $this->setProtocolVersion($result[1]);
        } else {
            $this->setProtocolVersion(null);
        }
    }

    /**
     * @param float $version
     */
    private function setProtocolVersion(float $version): void
    {
        $this->version = $version;
    }

    /**
     *
     */
    private function fetchBodyData()
    {
        $data = null;

        parse_str(file_get_contents('php://input'), $data);

        if ($data !== false) {
            $this->setBodyData($data);
        } else {
            $this->setBodyData(null);
        }
    }

    /**
     * @param array $body
     */
    private function setBodyData(array $data): void
    {
        $this->bodyData = $data;
    }

    /**
     * @return mixed
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): mixed
    {
        if (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        } else {
            return null;
        }
    }

    public function setHeader(string $name, mixed $value): void
    {
        if (array_key_exists($name, $this->headers)) {
            $this->headers[$name] = $value;
        } else {
            throw new Exception("Header does not exist!");
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return array
     */
    public function getBodyData(): array
    {
        return $this->bodyData;
    }

    /**
     * @return double
     */
    public function getProtocolVersion(): float
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
