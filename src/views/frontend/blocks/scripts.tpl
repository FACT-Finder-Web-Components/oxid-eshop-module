[{$smarty.block.parent}]
[{oxstyle include=$oViewConf->getModuleUrl("ffwebcomponents", "out/css/styles.css")}]

<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/vendor/custom-elements-es5-adapter.js')|escape}]"></script>
<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/vendor/webcomponents-loader.js')|escape}]"></script>
<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/bundle.js')|escape}]" defer></script>
<style>[unresolved]{opacity:0;display:none}</style>

<script>
document.addEventListener('ffReady', function () {
    factfinder.communication.fieldRoles = {"brand":"Brand","campaignProductNumber":"ProductNumber","deeplink":"ArticleUrl","description":"Description","displayProductNumber":"ProductNumber","ean":"EAN","imageUrl":"ImageURL","masterArticleNumber":"Master","price":"Price","productName":"Name","trackingProductNumber":"ProductNumber"};

    factfinder.communication.EventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === 'search') {
            event['cl'] = 'search_result';
        }
[{if $oView->getClassKey() neq 'search_result'}]

        if (event.type === 'search' && !event.__immediate) {
            delete event.type;
            window.location = '[{$oViewConf->getHomeLink()|escape:"javascript"}]' + factfinder.common.dictToParameterString(event);
        }
[{/if}]
    });
});
</script>
