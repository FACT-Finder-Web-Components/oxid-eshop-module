<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Oxid\Controller\FeedExportTrait;
use Omikron\FactFinder\Oxid\Export\AbstractFeed;
use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use Omikron\FactFinder\Oxid\Model\Export\FtpClient;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class ArticleFeedController extends AdminController
{
    use FeedExportTrait;

    /** @var string */
    protected $_sThisTemplate = 'admin/page/ajax_result.tpl';

    public function export()
    {
        $handle = tmpfile();
        $result = [];
        $feedParam = Request::getRequestParameter('exportType');
        $feedType  = $this->getFeedType($feedParam);

        try {
            $feed = oxNew($feedType);
            $feed->generate(oxNew(Csv::class, $handle));
            $result[]  = $this->translate('FF_ARTICLE_FEED_EXPORT_SUCCESS');
            $ftpClient = oxNew(FtpClient::class, oxNew(FtpParams::class));

            $ftpClient->upload($handle, $feed->getFileName($feedParam));
            $result[] = $this->translate('FF_ARTICLE_FEED_UPLOAD_SUCCESS');

            $pushImport = oxNew(PushImport::class);
            $pushImport->execute();
            $result[] = $this->translate('FF_ARTICLE_FEED_IMPORT_TRIGGERED');

            $this->addTplParam('success', true);
        } catch (\Exception $e) {
            $result[] = $e->getMessage();
        }

        $this->addTplParam('result', $result);
        fclose($handle);
    }

    protected function translate(string $message): string
    {
        return Registry::getLang()->translateString($message, null, true);
    }
}
