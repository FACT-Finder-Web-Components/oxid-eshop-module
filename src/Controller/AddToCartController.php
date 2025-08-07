<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Article;

class AddToCartController extends FrontendController
{
    public function addToCart(): void
    {
        $productNumber = Registry::getRequest()->getRequestParameter('productNumber');
        $amount = 1;
        $utilsView = Registry::getUtilsView();

        try {
            if (!$productNumber) {
                throw new \Exception('Product with number ' . $productNumber . ' does not exist');
            }

            $product = oxNew(Article::class);
            $productId = $this->getProductIdByNumber($productNumber);

            if (!$productId || !$product->load($productId) || !$product->isBuyable()) {
                throw new \Exception('Product with number ' . $productNumber . ' does not exist or is not buyable');
            }

            $basket = Registry::getSession()->getBasket();
            $basket->addToBasket($productId, $amount);
            $basket->calculateBasket(true);
            $utilsView->addErrorToDisplay('Product was added to the cart successfully.');
            $this->redirectToReferer();
        } catch (\Exception $e) {
            $utilsView->addErrorToDisplay('Error: ' . $e->getMessage(), false, false, 'error_message');
            $this->redirectToReferer();
        }
    }

    private function getProductIdByNumber($productNumber)
    {
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sQuery = "SELECT oxid FROM oxarticles WHERE oxartnum = ? AND oxactive = 1";

        return $db->getOne($sQuery, [$productNumber]);
    }

    private function redirectToReferer(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? Registry::getConfig()->getCurrentShopUrl();
        Registry::getUtils()->redirect($referer, false, 302);
    }
}
