[{$smarty.block.parent}]
[{assign var="recordDataJson" value=$oView->getProduct()|record_data_json}]

<script>
    registerAddToCartListener(
        '.js-oxProductForm',
        JSON.parse('[{ $recordDataJson }]')
    );
</script>
