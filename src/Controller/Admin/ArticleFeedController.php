<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Oxid\Model\Api\ClientFactory;
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use Omikron\FactFinder\Oxid\Model\Export\FtpClient;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;

class ArticleFeedController extends AdminController
{
    /** @var string */
    protected $_sThisTemplate = 'admin/page/ajax_result.tpl';

    /** @var FtpClient */
    private $ftpClient;

    /** @var ArticleFeed */
    private $articleFeed;

    /** @var PushImport */
    private $pushImport;

    /** @var array */
    private $result = [];

    /** @var bool */
    private $success = false;

    public function __construct()
    {
        $this->ftpClient   = new FtpClient(new FtpParams());
        $this->articleFeed = new ArticleFeed();
        $this->pushImport  = new PushImport(new ClientFactory());
    }

    public function export()
    {
        try {
            $file = $this->articleFeed->generate();
            $this->addTranslatedMessage('FF_ARTICLE_FEED_EXPORT_SUCCESS');
            $this->ftpClient->upload($file, $this->articleFeed->getFileName());
            $this->addTranslatedMessage('FF_ARTICLE_FEED_UPLOAD_SUCCESS');
            $this->pushImport->execute();
            $this->addTranslatedMessage('FF_ARTICLE_FEED_IMPORT_TRIGGERED');
            $this->success = true;
        } catch (\Exception $e) {
            $this->result  = $e->getMessage();
            $this->success = false;
        }
    }

    public function render(): string
    {
        $this->addTplParam('result', $this->result);
        $this->addTplParam('success', $this->success);
        return parent::render();
    }

    private function addTranslatedMessage(string $messageCode)
    {
        $this->result[] = Registry::getLang()->translateString($messageCode, null, true);
    }
}
