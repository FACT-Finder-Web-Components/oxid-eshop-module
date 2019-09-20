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

    /** @var TestConnection */
    private $testConnection;

    /** @var Request */
    private $request;

    /** @var string */
    private $result = '';

    /** @var bool */
    private $success = false;

    public function __construct()
    {
        parent::__construct();
        $this->testConnection = new TestConnection(new ClientFactory());
        $this->request        = Registry::get(Request::class);
    }

    public function testConnection()
    {
        $params = ['channel' => $this->request->getRequestEscapedParameter('channel'), 'verbose' => true];
        try {
            $this->testConnection->execute(
                $this->request->getRequestEscapedParameter('serverUrl'),
                $params + $this->getCredentials()->toArray()
            );
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

    /**
     * @return Credentials
     */
    private function getCredentials(): Credentials
    {
        return new Credentials(
            $this->request->getRequestEscapedParameter('username'),
            $this->request->getRequestEscapedParameter('password'),
            $this->request->getRequestEscapedParameter('prefix'),
            $this->request->getRequestEscapedParameter('postfix')
        );
    }
}
