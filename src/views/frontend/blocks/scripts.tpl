[{$smarty.block.parent}]
[{oxstyle include=$oViewConf->getModuleUrl("ffwebcomponents", "out/css/styles.css")}]

<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/vendor/custom-elements-es5-adapter.js')|escape}]"></script>
<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/vendor/webcomponents-loader.js')|escape}]"></script>
<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/bundle.js')|escape}]" defer></script>
<style>[unresolved]{opacity:0;display:none}</style>

<script>
document.addEventListener('ffReady', function () {
    factfinder.communication.fieldRoles = {"brand":"Brand","campaignProductNumber":"ProductNumber","deeplink":"ArticleUrl","description":"Description","displayProductNumber":"ProductNumber","ean":"EAN","imageUrl":"ImageURL","masterArticleNumber":"Master","price":"Price","productName":"Name","trackingProductNumber":"ProductNumber"};
[{if $oView->getClassKey() neq 'search_result'}]
    factfinder.communication.FFCommunicationEventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === 'search' && !event.__immediate) {
            window.location = '[{$oViewConf->getHomeLink()|escape:"javascript"}]' + factfinder.common.dictToParameterString(event) + '&cl=search_result';
        }
    });
[{/if}]
});
</script>
