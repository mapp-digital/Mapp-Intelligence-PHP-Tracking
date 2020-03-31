<?php

require_once __DIR__ . '/../MappIntelligence.php';
require_once __DIR__ . '/../Config/MappIntelligenceConfig.php';

/**
 * Class MappIntelligenceEnrichment
 */
class MappIntelligenceEnrichment extends MappIntelligenceConfig
{
    private $everId_;

    /**
     * MappIntelligenceEnrichment constructor.
     * @param array $config
     */
    protected function __construct($config = array())
    {
        parent::__construct($config);

        $this->everId_ = $this->getUserId();
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

        $domains = $this->config_['domain'];
        $referrerDomain = $this->getReferrerDomain($referrer);
        $isOwnDomain = false;
        for ($i = 0; $i < count($domains); $i++) {
            if (preg_match('/^\/.+\/$/', $domains[$i])) {
                try {
                    if (preg_match($domains[$i], $referrerDomain)) {
                        return true;
                    }
                } catch (Exception $e) {
                    // do nothing
                }
            } elseif ($domains[$i] === $referrerDomain) {
                return true;
            }
        }

        return $isOwnDomain;
    }

    /**
     * @return string
     */
    private function getReferrer()
    {
        $referrer = (array_key_exists('HTTP_REFERER', $_SERVER) && $_SERVER['HTTP_REFERER'])
            ? $_SERVER['HTTP_REFERER']
            : '0';

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
        $everId = '';
        $trackId = $this->config_['trackId'];

        if (array_key_exists('wtstp_eid', $_COOKIE)) {
            // SmartPixel cookie found
            $everId = $_COOKIE['wtstp_eid'];
        } elseif (array_key_exists('wteid_' . $trackId, $_COOKIE)) {
            // Track-Server cookie found
            $everId = $_COOKIE['wteid_' . $trackId];
        } elseif (array_key_exists('wt3_eid', $_COOKIE)) {
            // Pixel v3 - v5 cookie found
            $everIdValues = explode(';', $_COOKIE['wt3_eid']);
            for ($i = 0; $i < count($everIdValues); $i++) {
                if (strrpos($everIdValues[$i], $trackId . '|') !== false) {
                    $tmpEverId = str_replace($trackId . '|', '', $everIdValues[$i]);
                    $everId = explode('#', $tmpEverId);
                    $everId = $everId[0];
                    break;
                }
            }
        }

        return $everId;
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
     */
    private function setUserIdCookie($name, $value = '', $domain = '')
    {
        if (!$value) {
            $value = $this->everId_;
        }

        setcookie($name, $value, $this->getTimestamp() + 60*60*24*30*6, '/', $domain, true, true);
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
        return !preg_match('/\.(wt-.*|webtrekk|webtrekk-.*)\.net$/', $this->config_['trackDomain']);
    }

    /**
     * @return string
     */
    final protected function getUserAgent()
    {
        return array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * @return string
     */
    final protected function getUserIP()
    {
        return array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    /**
     * @return string
     */
    final protected function getRequestURI()
    {
        $host = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '';
        $request = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '';

        return $host . $request;
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
        if (is_array($this->config_['useParamsForDefaultPageName'])) {
            for ($i = 0; $i < count($this->config_['useParamsForDefaultPageName']); $i++) {
                $parameterKey = $this->config_['useParamsForDefaultPageName'][$i];
                $parameterValue = array_key_exists($parameterKey, $_GET) ? $_GET[$parameterKey] : false;
                if ($parameterValue) {
                    $parameterList[] = $parameterKey . '=' . $parameterValue;
                }
            }
        }

        if (count($parameterList) > 0) {
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
        return $this->everId_;
    }

    /**
     * @param string $pixelVersion
     * @param string $context
     */
    final public function setUserId($pixelVersion = '', $context = '')
    {
        if (!$this->everId_) {
            $this->everId_ = $this->generateUserId();

            if ($context === MappIntelligence::SERVER_SIDE_COOKIE) {
                if ($this->isOwnTrackDomain()) {
                    $cookieDomain = explode('.', $this->config_['trackDomain'], 2);
                    $cookieDomain = $cookieDomain[1];

                    // if it is an own tracking domain use this without sub domain
                    $this->setUserIdCookie('wteid_' . $this->config_['trackId'], '', $cookieDomain);
                }
            } else {
                switch ($pixelVersion) {
                    case MappIntelligence::V4:
                    case MappIntelligence::V5:
                        $cookieValue = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
                        $cookieValue .= ';' . $this->config_['trackId'] . '|' . $this->everId_;
                        $this->setUserIdCookie('wt3_eid', $cookieValue);
                        break;
                    case MappIntelligence::SMART:
                        $this->setUserIdCookie('wtstp_eid');
                        break;
                }
            }
        }
    }
}
