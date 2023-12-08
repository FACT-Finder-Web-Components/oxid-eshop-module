[{assign var="oConfig" value=$oView->getConfig()}]

[{if $oConfig->getConfigParam("ffUseForCategories") && $oView->getClassName() === 'alist'}]
    [{include file="ff/asn.tpl"}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]
