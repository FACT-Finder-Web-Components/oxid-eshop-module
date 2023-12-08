<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron\FactFinder\Oxid\Model\Export\UploadFactory;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use Psr\Http\Client\ClientExceptionInterface;

class TestConnectionController extends AdminController
{
    /** @var string */
    protected $_sThisTemplate = 'admin/page/ajax_result.tpl';

    /** @var string */
    protected string $result = '';

    /** @var bool */
    protected bool $success = false;

    public function testConnection(): void
    {
        try {
            $clientBuilder = oxNew(ClientBuilder::class)
                ->withServerUrl($this->param('serverUrl'))
                ->withCredentials($this->getCredentials());
            $adapterFactory = new AdapterFactory(
                $clientBuilder,
                $this->param('version'),
                $this->param('apiVersion')
            );
            $searchAdapter = $adapterFactory->getSearchAdapter();
            $searchAdapter->search($this->param('channel'), 'FACT-Finder version');

            $this->success = true;
            $this->result  = Registry::getLang()->translateString('FF_TEST_CONNECTION_SUCCESS', null, true);
        } catch (ClientExceptionInterface $e) {
            $this->result = $e->getMessage();
        }
    }

    public function testFtpConnection(): void
    {
        try {
            $params = array_reduce(array_keys($_POST), function (array $acc, string $key): array {
                $request = Registry::get(Request::class);
                return $acc + [$key => (string) $request->getRequestEscapedParameter($key)];
            }, []);

            $ftpUploader = UploadFactory::create($params);
            $ftpUploader->upload(tmpfile(), 'testconnection');

            $this->success = true;
            $this->result  = Registry::getLang()->translateString('FF_TEST_CONNECTION_SUCCESS', null, true);
        } catch (\Exception $e) {
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
