[{if $module_var === 'ffFtpKeyPassphrase'}]
<input type="text" class="txt" style="width: 250px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" id="ftp_key_passphrase" disabled>
[{else}]
    [{$smarty.block.parent}]
[{/if}]
