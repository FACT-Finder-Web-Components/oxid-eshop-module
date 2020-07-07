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
    protected $ftpClient;

    /** @var ArticleFeed */
    protected $articleFeed;

    /** @var PushImport */
    protected $pushImport;

    /** @var array */
    protected $result = [];

    /** @var bool */
    protected $success = false;

    public function __construct()
    {
        $this->ftpClient   = new FtpClient(new FtpParams());
        $this->articleFeed = new ArticleFeed();
        $this->pushImport  = new PushImport(new ClientFactory());
    }

    public function export()
    {
        $handle = fopen(OX_BASE_PATH . 'export/' . $this->articleFeed->getFileName(), 'w+');

        try {
            $this->articleFeed->generate($handle);
            $this->addTranslatedMessage('FF_ARTICLE_FEED_EXPORT_SUCCESS');

            $this->ftpClient->upload($handle, $this->articleFeed->getFileName());
            $this->addTranslatedMessage('FF_ARTICLE_FEED_UPLOAD_SUCCESS');

            $this->pushImport->execute();
            $this->addTranslatedMessage('FF_ARTICLE_FEED_IMPORT_TRIGGERED');

            $this->success = true;
        } catch (\Exception $e) {
            fclose($handle);
            $this->result = [$e->getMessage()];
        }
    }

    public function render(): string
    {
        $this->addTplParam('result', $this->result);
        $this->addTplParam('success', $this->success);
        return parent::render();
    }

    protected function addTranslatedMessage(string $message)
    {
        $this->result[] = Registry::getLang()->translateString($message, null, true);
    }
}
