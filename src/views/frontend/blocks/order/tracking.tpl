[{$smarty.block.parent}]

<ff-checkout-tracking>
[{foreach from=$order->getOrderArticles() item="oArticle"}]
    [{assign var="aPrice" value=$oArticle->getPrice()}]
    <ff-checkout-tracking-item record-id="[{$oArticle|record_id:"oxorderarticles"|escape}]"
                               count="[{$oArticle->oxorderarticles_oxamount}]"
                               price="[{$aPrice->getNettoPrice()|@number_format:2:".":""}]"></ff-checkout-tracking-item>
[{/foreach}]
</ff-checkout-tracking>
