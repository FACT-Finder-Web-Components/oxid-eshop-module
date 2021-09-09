<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Http;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Utils;
use OxidEsales\Eshop\Core\UtilsServer;

class Authentication
{
    /** @var Request */
    private $request;

    /** @var Utils */
    private $utils;

    /** @var UtilsServer */
    private $utilsServer;

    /** @var Config */
    private $config;

    public function __construct()
    {
        $this->request     = Registry::getRequest();
        $this->utils       = Registry::getUtils();
        $this->utilsServer = Registry::getUtilsServer();
        $this->config      = Registry::getConfig();
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        $server = $this->utilsServer->getServerVar();
        $user   = '';
        $pass   = '';

        if (empty($server['HTTP_AUTHORIZATION'])) {
            foreach ($server as $k => $v) {
                if (substr($k, -18) === 'HTTP_AUTHORIZATION' && !empty($v)) {
                    $server['HTTP_AUTHORIZATION'] = $v;

                    break;
                }
            }
        }

        if (isset($server['PHP_AUTH_USER']) && isset($server['PHP_AUTH_PW'])) {
            $user = $server['PHP_AUTH_USER'];
            $pass = $server['PHP_AUTH_PW'];
        } elseif (!empty($server['HTTP_AUTHORIZATION'])) {
            $auth              = $server['HTTP_AUTHORIZATION'];
            list($user, $pass) = explode(':', base64_decode(substr($auth, strpos($auth, ' ') + 1)));
        } elseif (!empty($server['Authorization'])) {
            $auth              = $server['Authorization'];
            list($user, $pass) = explode(':', base64_decode(substr($auth, strpos($auth, ' ') + 1)));
        }

        return [$user, $pass];
    }

    public function authenticate(string $username, string $password): bool
    {
        return strcmp($username, $this->getUsername()) === 0 && strcmp($password, $this->getPassword()) === 0;
    }

    /**
     * Set "auth failed" headers and returns to browser.
     */
    public function setAuthenticationFailed(string $realm = 'FACT-Finder')
    {
        $this->utils->setHeader('HTTP/1.1 401 Unauthorized');
        $this->utils->setHeader('WWW-Authenticate: Basic realm="' . $realm . '"');
        $this->utils->showMessageAndExit('Incorrect username or password');
    }

    private function getUsername(): string
    {
        return (string) $this->config->getConfigParam('ffHTTPExportUser');
    }

    private function getPassword(): string
    {
        return (string) $this->config->getConfigParam('ffHTTPExportPassword');
    }
}
