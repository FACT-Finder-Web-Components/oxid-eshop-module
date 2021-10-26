[{if $module_var === 'ffFtpKey'}]
    <textarea class="txtfield" style="width: 430px;" name="confstrs[[{$module_var}]]" wrap="off" cols="50" id="ftp_key">
        [{$confstrs.$module_var}]
    </textarea>
    [{else}]
    [{$smarty.block.parent}]
[{/if}]
