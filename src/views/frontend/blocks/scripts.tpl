[{$smarty.block.parent}]
[{oxstyle include=$oViewConf->getModuleUrl("ffwebcomponents", "out/css/styles.css")}]

<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/vendor/custom-elements-es5-adapter.js')|escape}]"></script>
<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/vendor/webcomponents-loader.js')|escape}]"></script>
<script src="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/js/ff-web-components/bundle.js')|escape}]" defer></script>
<script src="[{$oViewConf->getModuleUrl("ffwebcomponents", "out/js/utils.js")|escape}]"></script>
<style>[unresolved]{opacity:0;display:none}</style>

<script>
document.addEventListener('ffReady', function (ff) {
    factfinder.sdk = 'oe-v3.1.1';
    factfinder.communication.fieldRoles = {"brand":"Brand","campaignProductNumber":"ProductNumber","deeplink":"Deeplink","description":"Description","displayProductNumber":"ProductNumber","ean":"EAN","imageUrl":"ImageURL","masterArticleNumber":"Master","price":"Price","productName":"Name","trackingProductNumber":"ProductNumber"};

[{if $oView->getClassKey() neq "search_result"}]
    document.addEventListener('before-search', function (event) {
        if (['productDetail', 'getRecords'].lastIndexOf(event.detail.type) === -1) {
            event.preventDefault();
            const baseUrl =  '[{$oViewConf->getHomeLink()|escape:"javascript"}]';
            const params = ff.factfinder.common.dictToParameterString(factfinder.common.encodeDict(event.detail));
            window.location = baseUrl + (baseUrl.indexOf('?') > -1 ?  params.substr(1) : params) + '&cl=search_result'
        }
    });
[{/if}]

[{if $oView->getClassKey() eq "alist"}]
    ff.eventAggregator.addBeforeHistoryPushCallback(function (res, event, url) {
        url = url.replace(/filter=CategoryPath[^&]+&?/, '').replace(/filterCategoryPathROOT[^&]+&?/g, '');
        ff.factfinder.communication.Util.pushParameterToHistory(res, url, event);
        return false;
    });
[{/if}]
});
</script>
