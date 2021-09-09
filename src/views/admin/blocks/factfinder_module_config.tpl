[{$smarty.block.parent}]
    <link rel="stylesheet" type="text/css" href="[{$oViewConf->getModuleUrl('ffwebcomponents', 'out/admin/css/styles.css')}]"/>
[{if $var_group === 'ffExport'}]
    <select name="ffExportFeed" id="ffExportFeed" onchange="exportFeed()">
        <option value="">Please choose an option</option>
        <option value="product">Products</option>
        <option value="category">Categories</option>
    </select>

    <input type="button"
           id="ffTestConnection"
           class="confinput"
           name="ffTestConnection"
           value="Test Connection"
           onclick="testConnection()"/>

    <div id="spinner">
    </div>
    <div id="result" >
    </div>

    <script>
        function testConnection() {
            jQuery.ajax({
                url:      'index.php?cl=test_connection&fnc=testConnection&stoken=' + jQuery('input[name="stoken"]').val(),
                type:     'POST',
                dataType: 'json',
                data: getTestConnectionParams(),
                beforeSend: function() {
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
            let selectedValue = document.getElementById("ffExportFeed").value;

            jQuery.ajax({
                url:  'index.php?cl=article_feed&fnc=export&stoken=' + jQuery('input[name="stoken"]').val() + '&exportType=' + selectedValue,
                type: 'POST',
                beforeSend: function() {
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
                username:   jQuery('[name="confstrs[ffUsername]"]').val(),
                password:   jQuery('[name="confstrs[ffPassword]"]').val(),
                prefix:     jQuery('[name="confstrs[ffAuthPrefix]"]').val(),
                postfix:    jQuery('[name="confstrs[ffAuthPostfix]"]').val(),
                serverUrl:  jQuery('[name="confstrs[ffServerUrl]"]').val(),
                channel:    jQuery('[name="confaarrs[ffChannel][de]"]').val(),
                version:    jQuery('[name="confselects[ffApiVersion]"]').val(),
            }
        }
    </script>
[{/if}]
