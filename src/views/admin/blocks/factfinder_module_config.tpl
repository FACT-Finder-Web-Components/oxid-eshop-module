[{$smarty.block.parent}]
<link rel="stylesheet" type="text/css"
      href="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/admin/css/styles.css')}]"/>
[{if $var_group === 'ffExport'}]
<input type="button"
       id="ffExportFeed"
       class="confinput"
       name="ffExportFeed"
       value="Export feed"
       onclick="exportFeed()"/>
<input type="button"
       id="ffTestConnection"
       class="confinput"
       name="ffTestConnection"
       value="Test Connection"
       onclick="testConnection()"/>

<input type="button"
       id="ffTestFtpConnection"
       class="confinput"
       name="ffTestFtpConnection"
       value="Test FTP Connection"
       onclick="testFtpConnection()"/>

    <div id="spinner">
    </div>
    <div id="result">
    </div>

    <script>
        function testFtpConnection() {
            jQuery.ajax({
                url: 'index.php?cl=test_connection&fnc=testFtpConnection&stoken=' + jQuery('input[name="stoken"]').val(),
                type: 'POST',
                dataType: 'json',
                data: getTestFtpConnectionParams(),
                beforeSend: function () {
                    jQuery('#spinner').addClass('loading');
                },
                complete: function (response) {
                    setTimeout(
                            function () {
                                jQuery('#spinner').removeClass('loading');
                                jQuery('#result').html(response.responseText);
                            }, 1000
                    )
                }
            });
        }

        function testConnection() {
            jQuery.ajax({
                url: 'index.php?cl=test_connection&fnc=testConnection&stoken=' + jQuery('input[name="stoken"]').val(),
                type: 'POST',
                dataType: 'json',
                data: getTestConnectionParams(),
                beforeSend: function () {
                    jQuery('#spinner').addClass('loading');
                },
                complete: function (response) {
                    setTimeout(
                            function () {
                                jQuery('#spinner').removeClass('loading');
                                jQuery('#result').html(response.responseText);
                            }, 1000
                    )
                }
            });
        }

        function exportFeed() {
            jQuery.ajax({
                url: 'index.php?cl=article_feed&fnc=export&stoken=' + jQuery('input[name="stoken"]').val(),
                type: 'POST',
                beforeSend: function () {
                    jQuery('#spinner').addClass('loading');
                },
                complete: function (response) {
                    setTimeout(
                            function () {
                                jQuery('#spinner').removeClass('loading');
                                jQuery('#result').html(response.responseText);
                            }, 1000
                    )
                }
            });
        }

        function getTestConnectionParams() {
            return {
                username: jQuery('[name="confstrs[ffUsername]"]').val(),
                password: jQuery('[name="confstrs[ffPassword]"]').val(),
                prefix: jQuery('[name="confstrs[ffAuthPrefix]"]').val(),
                postfix: jQuery('[name="confstrs[ffAuthPostfix]"]').val(),
                serverUrl: jQuery('[name="confstrs[ffServerUrl]"]').val(),
                channel: jQuery('[name="confaarrs[ffChannel][de]"]').val(),
                version: jQuery('[name="confselects[ffApiVersion]"]').val(),
            }
        }

        function getTestFtpConnectionParams() {
            return {
                ftpType: jQuery('[name="confselects[ffFtpType]"]').val(),
                ftpHost: jQuery('[name="confstrs[ffFtpHost]"]').val(),
                ftpPort: jQuery('[name="confstrs[ffFtpPort]"]').val(),
                ftpUser: jQuery('[name="confstrs[ffFtpUser]"]').val(),
                ftpAuthType: jQuery('[name="confselects[ffFtpAuthType]"]').val(),
                ftpPassword: jQuery('[name="confstrs[ffFtpPassword]"]').val(),
                keyFilename: jQuery('[name="confstrs[ffFtpKeyFilename]"]').val(),
                keyPassphrase: jQuery('[name="confstrs[ffFtpKeyPassphrase]"]').val(),
                sslEnabled: jQuery('[name="confbools[ffSSLEnabled]"]').val(),
            }
        }
    </script>
    [{/if}]
