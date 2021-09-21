<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use Omikron\FactFinder\Oxid\Model\Export\Http\Authentication;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class ArticleFeedController extends FrontendController
{
    use FeedExportTrait;

    public function init()
    {
        /** @var Authentication $auth */
        $auth = oxNew(Authentication::class);
        if (!$auth->authenticate(...$auth->getCredentials())) {
            $auth->setAuthenticationFailed('FACT-Finder');
        }
    }

    public function export()
    {
        $feedType = $this->getFeedType(Request::getRequestParameter('exportType'));
        $oUtils   = Registry::getUtils();

        try {
            $feed   = oxNew($feedType);
            $handle = tmpfile();
            $feed->generate(oxNew(Csv::class, $handle));
            rewind($handle);

            $oUtils->setHeader('Pragma: public');
            $oUtils->setHeader('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            $oUtils->setHeader('Expires: 0');
            $oUtils->setHeader('Content-Disposition: attachment; filename=' . $feed->getFileName());
            $oUtils->setHeader('Content-Type: text/csv; charset=utf-8');
            fpassthru($handle);
        } finally {
            fclose($handle);
            $oUtils->showMessageAndExit('');
        }
    }
}
