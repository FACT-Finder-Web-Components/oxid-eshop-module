[{if $module_var === 'ffFtpHost'}]
<input type=text  class="txt" style="width: 250px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" [{$readonly}]
pattern="^(ftp:\/\/|ftps:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$">
[{else}]
    [{$smarty.block.parent}]
[{/if}]
