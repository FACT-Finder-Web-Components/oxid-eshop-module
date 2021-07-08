[{if $module_var === 'ffApiVersion'}]
    [{oxscript include=$oViewConf->getModuleUrl('ffwebcomponents','out/admin/js/api-version-selector.js') priority=1}]
    <select class="[{$var_type}]" name="confselects[[{$module_var}]]" onchange="selectMethod(this)">
        [{foreach from=$var_constraints.$module_var item=value key=key}]
            <option value="[{$value}]" [{if $confselects.$module_var === $value}]selected[{/if}]>[{$value|upper}]</option>
        [{/foreach}]
    </select>
[{else}]
    [{$smarty.block.parent}]
[{/if}]
