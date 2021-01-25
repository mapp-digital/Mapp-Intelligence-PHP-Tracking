<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../MappIntelligenceCookie.php';
// @codeCoverageIgnoreEnd

class MappIntelligenceServerCookie implements MappIntelligenceCookie
{
    /**
     * Name of the cookie.
     */
    private $name;
    /**
     * Value of the cookie.
     */
    private $value;
    /**
     * The domain within which this cookie should be presented.
     */
    private $domain = '';
    /**
     * The maximum age in seconds for this cookie.
     */
    private $expiry;
    /**
     * The path on the server to which the browser returns this cookie.
     */
    private $path = '';
    /**
     * true if the browser is sending cookies only over a secure protocol, or false if the browser can send
     * cookies using any protocol.
     */
    private $secure;
    /**
     * Marks or unmarks this cookie as HttpOnly.
     */
    private $httpOnly;

    /**
     * @param string $n Name of the cookie.
     * @param string $v Value of this cookie.
     */
    public function __construct($n, $v)
    {
        $this->name = $n;
        $this->value = $v;
    }

    /**
     * @param string $d Specifies the domain within which this cookie should be presented.
     */
    public function setDomain($d)
    {
        $this->domain = $d;
    }

    /**
     * @return string Gets the domain name of this cookie.
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the maximum age in seconds for this cookie.
     *
     * @param int $e Sets the maximum age in seconds for this cookie.
     */
    public function setMaxAge($e)
    {
        $this->expiry = $e;
    }

    /**
     * Gets the maximum age in seconds of this cookie.
     *
     * @return int Gets the maximum age in seconds of this cookie.
     */
    public function getMaxAge()
    {
        return $this->expiry;
    }

    /**
     * @param string $p Specifies a path for the cookie to which the client should return the cookie.
     */
    public function setPath($p)
    {
        $this->path = $p;
    }

    /**
     * @return string Returns the path on the server to which the browser returns this cookie.
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param bool $s Indicates to the browser whether the cookie should only be sent using a secure protocol,
     *                such as HTTPS or SSL.
     */
    public function setSecure($s)
    {
        $this->secure = $s;
    }

    /**
     * @return bool Returns true if the browser is sending cookies only over a secure protocol, or false if the browser
     *              can send cookies using any protocol.
     */
    public function isSecure()
    {
        return $this->secure;
    }

    /**
     * @return string Returns the name of the cookie.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string Gets the current value of this cookie.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param bool $isHttpOnly Marks or unmarks this cookie as HttpOnly.
     */
    public function setHttpOnly($isHttpOnly)
    {
        $this->httpOnly = $isHttpOnly;
    }

    /**
     * @return bool Checks whether this cookie has been marked as HttpOnly.
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }
}
