<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use Omikron\FactFinder\Oxid\Model\Export\Http\Authentication;
use Omikron\FactFinder\Oxid\Model\Export\Http\CsvResponse;
use OxidEsales\Eshop\Application\Controller\FrontendController;

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
        /** @var ArticleFeed $articleFeed */
        $articleFeed = oxNew(ArticleFeed::class);
        $output      = $articleFeed->generate();
        $response = new CsvResponse($output, $articleFeed->getFileName());

        return $response->send();
    }
}
