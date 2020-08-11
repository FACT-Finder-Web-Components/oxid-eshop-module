<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use Omikron\FactFinder\Oxid\Model\Export\Http\Authentication;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;

class ArticleFeedController extends FrontendController
{
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
        $oUtils = Registry::getUtils();

        try {
            /** @var ArticleFeed $feed */
            $feed   = oxNew(ArticleFeed::class);
            $handle = tmpfile();
            $feed->generate($handle);

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
