[{$smarty.block.parent}]
[{assign var="recordId" value=$oView->getProduct()|record_id}]

<ff-campaign-product record-id="[{$recordId|escape}]"></ff-campaign-product>
[{include file="ff/campaign/feedbacktext.tpl" flag="is-product-campaign"}]
[{include file="ff/campaign/pushed_products.tpl" flag="is-product-campaign"}]
[{include file="ff/recommendation.tpl" recordId=$recordId}]
[{include file="ff/similar.tpl" recordId=$recordId}]
