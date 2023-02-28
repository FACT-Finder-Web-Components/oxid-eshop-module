[{$smarty.block.parent}]
[{assign var="recordDataJson" value=$oView->getProduct()|record_data_json}]
[{assign var="useSidAsUserId" value=$oView->getProduct()|ff_use_sid_a_suser_id}]

<script>

    registerAddToCartListener({
        selector: '.js-oxProductForm',
        productData: JSON.parse('[{ $recordDataJson }]'),
        useSidAsUserId: '[{$useSidAsUserId|escape:"javascript"}]'
    });
</script>
