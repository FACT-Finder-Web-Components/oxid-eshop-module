<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Oxid\Export\ArticleFeed;
use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use Omikron\FactFinder\Oxid\Model\Export\UploadFactory;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;

class ArticleFeedController extends AdminController
{
    /** @var string */
    protected $_sThisTemplate = 'admin/page/ajax_result.tpl';

    public function export(): void
    {
        $handle    = tmpfile();
        $result    = [];

        try {
            $articleFeed = oxNew(ArticleFeed::class);
            $articleFeed->generate(oxNew(Csv::class, $handle));
            $result[] = $this->translate('FF_ARTICLE_FEED_EXPORT_SUCCESS');

            $uploader = UploadFactory::create();
            $uploader->upload($handle, $articleFeed->getFileName());
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
