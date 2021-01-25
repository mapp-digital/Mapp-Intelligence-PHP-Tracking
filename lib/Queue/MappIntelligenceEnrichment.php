<?php

require_once __DIR__ . '/../MappIntelligenceParameter.php';
require_once __DIR__ . '/../Config/MappIntelligenceConfig.php';

/**
 * Class MappIntelligenceEnrichment
 */
class MappIntelligenceEnrichment
{
    private $domains;
    private $trackId;
    private $trackDomain;
    private $useParamsForDefaultPageName;
    private $everId;
    private $userAgent;
    private $remoteAddress;
    private $referrerURL;
    private $requestURL;
    private $cookie;

    /**
     * MappIntelligenceEnrichment constructor.
     * @param array $config
     */
    protected function __construct($config = array())
    {
        $this->domains = $config['domain'];
        $this->trackId = $config['trackId'];
        $this->trackDomain = $config['trackDomain'];
        $this->useParamsForDefaultPageName = $config['useParamsForDefaultPageName'];

        $this->userAgent = $config['userAgent'];
        $this->remoteAddress = $config['remoteAddress'];
        $this->referrerURL = $config['referrerURL'];
        $this->requestURL = $config['requestURL'];
        $this->cookie = $config['cookie'];

        $this->everId = $this->getUserId();
    }

    /**
     * @return int
     */
    private function getTimestamp()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * @param string $referrer
     * @return string
     */
    private function getReferrerDomain($referrer)
    {
        $referrerSplit = explode('/', $referrer);
        if (array_key_exists('2', $referrerSplit)) {
            return mb_strtolower($referrerSplit['2'], 'UTF-8');
        }

        return '';
    }

    /**
     * @param string $referrer
     * @return bool
     */
    private function isOwnDomain($referrer)
    {
        if ($referrer === '0') {
            return false;
        }

        $referrerDomain = $this->getReferrerDomain($referrer);
        $isOwnDomain = false;
        for ($i = 0; $i < count($this->domains); $i++) {
            try {
                if ($this->domains[$i] === $referrerDomain || preg_match($this->domains[$i], $referrerDomain)) {
                    return true;
                }
            } catch (Exception $e) {
                // do nothing
            }
        }

        return $isOwnDomain;
    }

    /**
     * @return string
     */
    private function getReferrer()
    {
        $referrer = (!empty($this->referrerURL)) ? $this->referrerURL : '0';

        if ($this->isOwnDomain($referrer)) {
            $referrer = '1';
        }

        return rawurlencode($referrer);
    }

    /**
     * @return string
     */
    private function getUserId()
    {
        $eId = '';
        if (array_key_exists(MappIntelligenceParameter::$SMART_PIXEL_COOKIE_NAME, $this->cookie)) {
            // SmartPixel cookie found
            $eId = $this->cookie[MappIntelligenceParameter::$SMART_PIXEL_COOKIE_NAME];
        } elseif (array_key_exists(
            MappIntelligenceParameter::$SERVER_COOKIE_NAME_PREFIX . $this->trackId,
            $this->cookie
        )) {
            // Track-Server cookie found
            $eId = $this->cookie[MappIntelligenceParameter::$SERVER_COOKIE_NAME_PREFIX . $this->trackId];
        } elseif (array_key_exists(MappIntelligenceParameter::$PIXEL_COOKIE_NAME, $this->cookie)) {
            // Pixel v3 - v5 cookie found
            $everIdValues = explode(';', $this->cookie[MappIntelligenceParameter::$PIXEL_COOKIE_NAME]);
            for ($i = 0; $i < count($everIdValues); $i++) {
                if (strrpos($everIdValues[$i], $this->trackId . '|') !== false) {
                    $tmpEverId = str_replace($this->trackId . '|', '', $everIdValues[$i]);
                    $eId = explode('#', $tmpEverId);
                    $eId = $eId[0];
                    break;
                }
            }
        }

        return $eId;
    }

    /**
     * @return string
     */
    private function generateUserId()
    {
        return '8' . $this->getTimestamp() . rand(10000, 99999);
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $domain
     *
     * @return MappIntelligenceCookie
     */
    private function setUserIdCookie($name, $value = '', $domain = '')
    {
        if (empty($value)) {
            $value = $this->everId;
        }

        $everIdCookie = new MappIntelligenceServerCookie($name, $value);
        if (!empty($domain)) {
            $everIdCookie->setDomain($domain);
        }

        $everIdCookie->setMaxAge($this->getTimestamp() + 60*60*24*30*6);
        $everIdCookie->setPath('/');
        $everIdCookie->setSecure(true);
        $everIdCookie->setHttpOnly(true);

        setcookie(
            $everIdCookie->getName(),
            $everIdCookie->getValue(),
            $everIdCookie->getMaxAge(),
            $everIdCookie->getPath(),
            $everIdCookie->getDomain(),
            $everIdCookie->isSecure(),
            $everIdCookie->isHttpOnly()
        );

        return $everIdCookie;
    }

    /**
     * @return bool
     */
    private function isOwnTrackDomain()
    {
        /**
         * .webtrekk.net          (Germany)
         * .wt-eu02.net           (Germany)
         * .webtrekk-us.net       (USA)
         * .webtrekk-asia.net     (Singapur)
         * .wt-sa.net             (Brasilien)
         */
        return !preg_match('/\.(wt-.*|webtrekk|webtrekk-.*)\.net$/', $this->trackDomain);
    }

    /**
     * @param $url
     * @return string
     */
    private function unParseURI($url)
    {
        $host = isset($url['host']) ? $url['host'] : '';
        $port = isset($url['port']) ? ':' . $url['port'] : '';
        $user = isset($url['user']) ? $url['user'] : '';
        $pass = isset($url['pass']) ? ':' . $url['pass']  : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($url['path']) ? $url['path'] : '';
        $query = isset($url['query']) ? '?' . $url['query'] : '';

        return "$user$pass$host$port$path$query";
    }

    /**
     * @param string $pixelVersion
     * @param string $context
     *
     * @return MappIntelligenceCookie|null
     */
    private function userIdCookie($pixelVersion = '', $context = '')
    {
        $c = null;
        if (empty($this->everId)) {
            $this->everId = $this->generateUserId();

            if ($context === MappIntelligence::SERVER_SIDE_COOKIE) {
                if ($this->isOwnTrackDomain()) {
                    $cookieDomain = explode('.', $this->trackDomain, 2);
                    $cookieDomain = $cookieDomain[1];

                    // if it is an own tracking domain use this without sub domain
                    $c = $this->setUserIdCookie(
                        MappIntelligenceParameter::$SERVER_COOKIE_NAME_PREFIX . $this->trackId,
                        '',
                        $cookieDomain
                    );
                }
            } else {
                switch ($pixelVersion) {
                    case MappIntelligence::V4:
                    case MappIntelligence::V5:
                        $cookieValue = array_key_exists(MappIntelligenceParameter::$PIXEL_COOKIE_NAME, $this->cookie)
                            ? $this->cookie[MappIntelligenceParameter::$PIXEL_COOKIE_NAME]
                            : '';
                        $cookieValue .= ';' . $this->trackId . '|' . $this->everId;
                        $c = $this->setUserIdCookie(MappIntelligenceParameter::$PIXEL_COOKIE_NAME, $cookieValue);
                        break;
                    case MappIntelligence::SMART:
                        $c = $this->setUserIdCookie(MappIntelligenceParameter::$SMART_PIXEL_COOKIE_NAME);
                        break;
                    default:
                        break;
                }
            }
        }

        return $c;
    }

    /**
     * @return string
     */
    final protected function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    final protected function getRemoteAddress()
    {
        return $this->remoteAddress;
    }

    /**
     * @return string
     */
    final protected function getRequestURI()
    {
        if (empty($this->requestURL)) {
            return '';
        }

        return $this->unParseURI($this->requestURL);
    }

    /**
     * @param string $pageName
     * @return string
     */
    final protected function getMandatoryQueryParameter($pageName)
    {
        return '600,' . rawurlencode($pageName) . ',,,,,' . $this->getTimestamp() . ',' . $this->getReferrer() . ',,';
    }

    /**
     * @return string
     */
    final protected function getDefaultPageName()
    {
        $plainUrl = explode('?', $this->getRequestURI());
        $plainUrl = $plainUrl[0];

        $parameterList = array();
        if (is_array($this->useParamsForDefaultPageName)) {
            for ($i = 0; $i < count($this->useParamsForDefaultPageName); $i++) {
                $parameterKey = $this->useParamsForDefaultPageName[$i];
                $parameterValue = array_key_exists($parameterKey, $_GET) ? $_GET[$parameterKey] : false;
                if ($parameterValue) {
                    $parameterList[] = $parameterKey . '=' . $parameterValue;
                }
            }
        }

        if (!empty($parameterList)) {
            $plainUrl .= '?' . implode('&', $parameterList);
        }

        if (!$plainUrl) {
            $plainUrl = '0';
        }

        return mb_strtolower($plainUrl, 'UTF-8');
    }

    /**
     * @return string
     */
    final protected function getEverId()
    {
        return $this->everId;
    }

    /**
     * @param string $pixelVersion
     * @param string $context
     */
    final public function setUserId($pixelVersion = '', $context = '')
    {
        $this->userIdCookie($pixelVersion, $context);
    }

    /**
     * @param string $pixelVersion
     * @param string $context
     *
     * @return MappIntelligenceCookie
     */
    final public function getUserIdCookie($pixelVersion = '', $context = '')
    {
        return $this->userIdCookie($pixelVersion, $context);
    }
}
