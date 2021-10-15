[{if $module_var === 'ffFtpKeyFilename'}]
<input type=text class="txt" style="width: 250px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" id="sftp_key_filename" disabled>
    [{else}]
    [{$smarty.block.parent}]
[{/if}]
