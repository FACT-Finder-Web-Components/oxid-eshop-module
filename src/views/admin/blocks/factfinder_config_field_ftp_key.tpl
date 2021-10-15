[{if $module_var === 'ffFtpKey'}]
    <textarea class="txtfield" style="width: 430px;" name="confaarrs[[{$module_var}]]" wrap="off" [{$readonly}] id="ftp_key" disabled>
        [{$confaarrs.$module_var}]
    </textarea>
    [{else}]
    [{$smarty.block.parent}]
[{/if}]
