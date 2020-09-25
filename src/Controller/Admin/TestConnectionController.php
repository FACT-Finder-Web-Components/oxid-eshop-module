<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Oxid\Exception\ResponseException;
use Omikron\FactFinder\Oxid\Model\Api\Credentials;
use Omikron\FactFinder\Oxid\Model\Api\Resource\Builder as ResourceBuilder;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class TestConnectionController extends AdminController
{
    /** @var string */
    protected $_sThisTemplate = 'admin/page/ajax_result.tpl';

    /** @var string */
    protected $result = '';

    /** @var bool */
    protected $success = false;

    public function testConnection()
    {
        try {
            $resource = oxNew(ResourceBuilder::class)
                ->withServerUrl($this->param('serverUrl'))
                ->withApiVersion($this->param('version'))
                ->withCredentials($this->getCredentials())
                ->build();

            $resource->search('FACT-Finder version', $this->param('channel'));

            $this->success = true;
            $this->result  = Registry::getLang()->translateString('FF_TEST_CONNECTION_SUCCESS', null, true);
        } catch (ResponseException $e) {
            $this->result = $e->getMessage();
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
        return new Credentials(...array_map([$this, 'param'], ['username', 'password', 'prefix', 'postfix']));
    }

    protected function param(string $key): string
    {
        /** @var Request $request */
        $request = Registry::get(Request::class);
        return (string) $request->getRequestEscapedParameter($key);
    }
}
