[{if $module_var === 'ffServerUrl'}]
<input type=url  class="txt" style="width: 250px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" [{$readonly}]>
[{else}]
    [{$smarty.block.parent}]
[{/if}]
