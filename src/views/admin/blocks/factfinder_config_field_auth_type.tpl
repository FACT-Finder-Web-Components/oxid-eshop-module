[{if $module_var === 'ffFtpAuthType'}]
    <select class="[{$var_type}]" name="confselects[[{$module_var}]]" id="sftp_auth_type">
        [{foreach from=$var_constraints.$module_var item=value key=key}]
        <option value="[{$value}]" [{if ($confselects.$module_var==$value)}]selected[{/if}]>[{$value|capitalize}]</option>
        [{/foreach}]
    </select>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const authTypeSelectedValue = document.getElementById('sftp_auth_type').value;

            if (authTypeSelectedValue === 'key') {
                document.getElementById('ftp_password').disabled = true;
                document.getElementById('ftp_key').disabled = false;
                document.getElementById('ftp_key_passphrase').disabled = false;
                document.getElementById('sftp_key_filename').disabled = false;
            } else {
                document.getElementById('ftp_password').disabled = false;
                document.getElementById('ftp_key').disabled = true;
                document.getElementById('sftp_key_filename').disabled = true;
            }
         })

        document.querySelector('[name="confselects[[{$module_var}]]"]').addEventListener('change',function(){
            const selectedValue = this.value;

            if (selectedValue === 'key') {
                document.getElementById('ftp_password').disabled = true;
                document.getElementById('ftp_key').disabled = false;
                document.getElementById('ftp_key_passphrase').disabled = false;
                document.getElementById('sftp_key_filename').disabled = false;
            } else {
                document.getElementById('ftp_password').disabled = false;
                document.getElementById('ftp_key').disabled = true;
                document.getElementById('ftp_key_passphrase').disabled = true;
                document.getElementById('sftp_key_filename').disabled = true;
            }
        });
    </script>
    [{else}]
    [{$smarty.block.parent}]
[{/if}]
