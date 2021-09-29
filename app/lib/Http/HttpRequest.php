<?php

require_once("app/lib/Http/Session.php");
require_once("app/lib/Http/SessionFactory.php");

class HttpRequest
{
    private $uri;

    private $method;

    private $headers;

    // http version
    private $protocolVersion;

    // php session
    private $session;

    private $bodyData;

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

        $this->fetchProtocolVersion();
        $this->fetchBodyData();
    }

    /**
     * @param mixed $headers
     */
    private function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

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
     * @param mixed $version
     */
    private function setProtocolVersion($version): void
    {
        $this->version = $version;
    }

    private function fetchBodyData()
    {
        $data = null;

        if (isset($_REQUEST)) {
            $this->setBodyData($_REQUEST);
        } else {
            $data = file_get_contents('php://input');

            if ($data !== false) {
                $this->setBodyData($data);
            } else {
                $this->setBodyData(null);
            }
        }
    }

    /**
     * @param mixed $body
     */
    private function setBodyData($data): void
    {
        $this->bodyData = $data;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader($name)
    {
        if (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getBodyData()
    {
        return $this->bodyData;
    }

    public function getSession()
    {
        // return SESSION_CLASS(initialised PHP_SESSION_ID cookie)
    }

    /**
     * @param mixed $session
     */
    public function setSession($session): void
    {
        $this->session = $session;
    }

    /**
     * @param mixed $uri
     */
    private function setUri($uri): void
    {
        $this->uri = $uri;
    }
}