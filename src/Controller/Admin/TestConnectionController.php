<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Oxid\Exception\ResponseException;
use Omikron\FactFinder\Oxid\Model\Api\ClientFactory;
use Omikron\FactFinder\Oxid\Model\Api\Credentials;
use Omikron\FactFinder\Oxid\Model\Api\TestConnection;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class TestConnectionController extends AdminController
{
    /** @var string */
    protected $_sThisTemplate = 'admin/page/ajax_result.tpl';

    /** @var string */
    private $result = '';

    /** @var bool */
    private $success = false;

    public function testConnection()
    {
        try {
            $testConnection = oxNew(TestConnection::class, oxNew(ClientFactory::class)->create(), $this->param('version'));
            $testConnection->execute($this->param('serverUrl'), $this->param('channel'), $this->getCredentials());
            $this->result  = Registry::getLang()->translateString('FF_TEST_CONNECTION_SUCCESS', null, true);
            $this->success = true;
        } catch (ResponseException $e) {
            $this->result  = $e->getMessage();
            $this->success = false;
        }
    }

    public function render(): string
    {
        $ret = parent::render();
        $this->addTplParam('result', $this->result);
        $this->addTplParam('success', $this->success);
        return $ret;
    }

    protected function getCredentials(): Credentials
    {
        return new Credentials(
            $this->param('username'),
            $this->param('password'),
            $this->param('prefix'),
            $this->param('postfix')
        );
    }

    protected function param(string $key): string
    {
        /** @var Request $request */
        $request = Registry::get(Request::class);
        return (string) $request->getRequestEscapedParameter($key);
    }
}
