[{if $module_var === 'ffExportAttributes'}]
    [{oxscript include=$oViewConf->getModuleUrl('ffwebcomponents','out/js/ff-web-components/vendor/custom-elements-es5-adapter.js') priority=1}]
    [{oxscript include=$oViewConf->getModuleUrl('ffwebcomponents','out/js/ff-web-components/vendor/webcomponents-loader.js') priority=2}]
    [{oxscript include=$oViewConf->getModuleUrl('ffwebcomponents','out/admin/js/attribute-rows.js') priority=3}]
    <attribute-rows selected-attributes='[{$selectedAttributes|@json_encode}]' available-attributes='[{$availableAttributes|@json_encode}]'></attribute-rows>
[{else}]
  [{$smarty.block.parent}]
[{/if}]
