[{if $module_var === 'ffExportAttributes'}]
    [{oxscript include=$oViewConf->getModuleUrl('ffwebcomponents','out/js/ff-web-components/vendor/custom-elements-es5-adapter.js') priority=1}]
    [{oxscript include=$oViewConf->getModuleUrl('ffwebcomponents','out/js/ff-web-components/vendor/webcomponents-loader.js') priority=2}]
    [{oxscript include=$oViewConf->getModuleUrl('ffwebcomponents','out/admin/js/attribute-rows.js') priority=3}]
    <attribute-rows headers='Name, Multi-Attribute' selected-attributes='[{$selectedAttributes}]' available-attributes='[{$availableAttributes}]'></attribute-rows>
[{else}]
  [{$smarty.block.parent}]
[{/if}]
