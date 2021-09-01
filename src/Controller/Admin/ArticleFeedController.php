<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Oxid\Export\CategoryFeed;
use Omikron\FactFinder\Oxid\Export\ProductFeed;
use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use Omikron\FactFinder\Oxid\Model\Export\FtpClient;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;

class ArticleFeedController extends AdminController
{
    /** @var string */
    protected $_sThisTemplate = 'admin/page/ajax_result.tpl';

    public function getFeedTypes(): array
    {
        return [
            'product' => ProductFeed::class,
            'category' => CategoryFeed::class,
        ];
    }

    public function getFeedType($requestedType): string
    {
        $feedTypes = $this->getFeedTypes();
        if (!isset($feedTypes[$requestedType])) {
            throw new \Exception(sprintf('Unknown feed type %s', $requestedType));
        }

        return $feedTypes[$requestedType];
    }

    public function export()
    {
        $handle = tmpfile();
        $result = [];
        $feedType = $this->getFeedType($_GET['exportType']);

        try {
            $feed = oxNew($feedType);
            $feed->generate(oxNew(Csv::class, $handle));
            $result[] = $this->translate('FF_ARTICLE_FEED_EXPORT_SUCCESS');

            $ftpClient = oxNew(FtpClient::class, oxNew(FtpParams::class));
            $ftpClient->upload($handle, $feed->getFileName());
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
