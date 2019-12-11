[{assign var="oConfig" value=$oView->getConfig()}]

[{if $oConfig->getConfigParam("ffCampaigns")}]
    [{include file="ff/campaign/feedbacktext.tpl" flag="is-shopping-cart-campaign"}]
[{/if}]

[{$smarty.block.parent}]

[{if $oView->getBasketArticles()|@count}]
    [{assign var="recordId" value=$oView->getBasketArticles()|record_id}]

    [{if $oConfig->getConfigParam("ffCampaigns")}]
        <ff-campaign-shopping-cart record-id="[{','|implode:$recordId|escape}]"></ff-campaign-shopping-cart>
    [{/if}]

    [{if $oConfig->getConfigParam("ffPushedProducts")}]
        [{include file="ff/campaign/pushed_products.tpl" flag="is-shopping-cart-campaign"}]
    [{/if}]
[{/if}]
