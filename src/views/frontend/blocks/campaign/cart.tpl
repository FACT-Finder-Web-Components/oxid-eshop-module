[{include file="ff/campaign/feedbacktext.tpl" flag="is-shopping-cart-campaign"}]

[{$smarty.block.parent}]

[{if $oView->getBasketArticles()|@count}]
    [{assign var="recordId" value=$oView->getBasketArticles()|record_id}]
    <ff-campaign-shopping-cart record-id="[{','|implode:$recordId|escape}]"></ff-campaign-shopping-cart>
    [{include file="ff/campaign/pushed_products.tpl" flag="is-shopping-cart-campaign"}]
[{/if}]
