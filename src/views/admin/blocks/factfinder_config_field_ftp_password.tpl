[{if $module_var === 'ffFtpPassword'}]
<input type=text class="txt" style="width: 250px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" id="ftp_password">
[{else}]
    [{$smarty.block.parent}]
[{/if}]
