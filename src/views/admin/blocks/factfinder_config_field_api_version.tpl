[{if $module_var === 'ffVersion'}]
    <select class="[{$var_type}]" name="confselects[[{$module_var}]]">
        [{foreach from=$var_constraints.$module_var item=value key=key}]
        <option value="[{$value}]" [{if ($confselects.$module_var==$value)}]selected[{/if}]>[{$value|upper}]</option>
        [{/foreach}]
    </select>
    [{else}]
    [{$smarty.block.parent}]
    [{/if}]

<script>
    (function selectApiListener(selector) {
        const changeDisabled = function (value) {
            document.querySelectorAll('[name^="confstrs[ffAuth"]').forEach(function (element) {
                element.disabled = value;
            });
        };
        const adjustAuthFields = function (selector) {
            if (selector.value === 'ng') {
                changeDisabled(true);
            } else {
                changeDisabled(false);
            }
        };

        selector.addEventListener('change', function (event) {
            adjustAuthFields(event.target);
        });
        adjustAuthFields(selector);

    })(document.querySelector('[name="confselects[[{$module_var}]]"]'));
</script>
