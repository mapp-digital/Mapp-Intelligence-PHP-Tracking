<?php

/**
 * Interface MappIntelligenceCookie
 */
interface MappIntelligenceCookie
{
    /**
     * @param string $domain Specifies the domain within which this cookie should be presented.
     */
    public function setDomain($domain);

    /**
     * @return string Gets the domain name of this cookie.
     */
    public function getDomain();

    /**
     * Sets the maximum age in seconds for this cookie.
     *
     * @param integer $expiry Sets the maximum age in seconds for this cookie.
     */
    public function setMaxAge($expiry);

    /**
     * Gets the maximum age in seconds of this cookie.
     *
     * @return integer Gets the maximum age in seconds of this cookie.
     */
    public function getMaxAge();

    /**
     * @param string $uri Specifies a path for the cookie to which the client should return the cookie.
     */
    public function setPath($uri);

    /**
     * @return string Returns the path on the server to which the browser returns this cookie.
     */
    public function getPath();

    /**
     * @param bool $flag Indicates to the browser whether the cookie should only be sent using a secure protocol,
     *                   such as HTTPS or SSL.
     */
    public function setSecure($flag);

    /**
     * @return bool Returns true if the browser is sending cookies only over a secure protocol, or false if the browser
     *              can send cookies using any protocol.
     */
    public function isSecure();

    /**
     * @return string Returns the name of the cookie.
     */
    public function getName();

    /**
     * @return string Gets the current value of this cookie.
     */
    public function getValue();

    /**
     * @param bool $isHttpOnly Marks or unmarks this cookie as HttpOnly.
     */
    public function setHttpOnly($isHttpOnly);

    /**
     * @return bool Checks whether this cookie has been marked as HttpOnly.
     */
    public function isHttpOnly();
}
