[{$smarty.block.parent}]
[{assign var="recordId" value=$oView->getProduct()|record_id}]

<script>
    registerAddToCartListener('.js-oxProductForm',"[{$recordId}]");
</script>
