[{$smarty.block.parent}]

[{assign var="recordId" value=$oView->getProduct()|record_id}]

[{if $oConfig->getConfigParam("ffCampaigns")}]
    [{include file="ff/campaign/feedbacktext.tpl" flag="is-product-campaign"}]
    <ff-campaign-product record-id="[{$recordId|escape}]"></ff-campaign-product>
[{/if}]

[{if $oConfig->getConfigParam("ffPushedProducts")}]
    [{include file="ff/campaign/pushed_products.tpl" flag="is-product-campaign"}]
[{/if}]

[{if $oConfig->getConfigParam("ffRecommendations")}]
    [{include file="ff/recommendation.tpl" recordId=$recordId}]
[{/if}]

[{if $oConfig->getConfigParam("ffSimilarProducts")}]
    [{include file="ff/similar.tpl" recordId=$recordId}]
[{/if}]
