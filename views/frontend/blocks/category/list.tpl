[{assign var="oConfig" value=$oView->getConfig()}]

[{if ($oConfig->getConfigParam("ffUseForCategories") || $isSearchResult) && $oView->getArticleCount()}]
    [{assign var="sidebar" value="Left"}]
    [{include file="ff/search/result.tpl"}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]
