[{if $module_var === 'ffExportAttributes'}]
    <div class="attributes_wrapper">
        <div>[{$selectedAttributes|json_encode}]</div>

        <table id="attributes" style="width:300px">
            <thead>
            <tr>
                <th>Name</th>
                <th>Multi-Attribute</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <input type="button" onclick="addRow()" value="Add"/>
    </div>

    <script type="text/javascript">
        const rowTemplate = `
                <tr class="attribute-row" id="ID_PLACEHOLDER">
                    <td>
                        <select id="[{$module_var}]-ID_PLACEHOLDER" class="attribute-select"  name="confaarrs[[{$module_var}]][ID_PLACEHOLDER][id]">
                            [{foreach from=$availableAttributes item=attribute key=key}]
                             <option value="[{$key}]">[{$attribute}]</option>
                            [{/foreach}]
                        </select>
                    </td>
                    <td>
                        <select id="type-[{$module_var}]-ID_PLACEHOLDER" class="separate-column-select" name="confaarrs[[{$module_var}]][ID_PLACEHOLDER][multi]">
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                        </select>
                </td>
                <td>
                    <input type="button" onclick="deleteRow('ID_PLACEHOLDER')" style="width:12px; height: 11px;background:url('[{$oViewConf->getImageUrl()}]delete_button.gif') no-repeat; background-size: cover; padding:0; margin: 0; border: 0">
                    </input>
                </td>
                </tr>`;

        const selectedAttributesJSON = JSON.parse('[{$selectedAttributes|@json_encode}]');
        const selectedAttributes = [];

        for (var id in selectedAttributesJSON) {
            selectedAttributes.push(selectedAttributesJSON[id]);
        }
        for (var i = 0; i < selectedAttributes.length; i++) {
            createRow(i, selectedAttributes[i]);
        }

        function createRow(elementId, selectedAttribute = false) {
            const template = rowTemplate.replace(new RegExp('ID_PLACEHOLDER', 'g'), elementId);
            const element = document.createElement('tr');

            element.id = elementId;
            element.classList.add('attribute-row');
            element.innerHTML = template;

            if (selectedAttribute !== false) {
                const attrOption = element.querySelector('option[value="' + selectedAttribute.id + '"]');
                attrOption.setAttribute('selected', 'selected');

                const multiOption = element.querySelector('.separate-column-select option[value="' + selectedAttribute.multi + '"]');
                multiOption.setAttribute('selected', 'selected');
            }

            document.querySelector('#attributes tbody').appendChild(element);

        }

        function addRow() {
            createRow(document.querySelectorAll('#attributes tr').length);
        }

        function deleteRow(id) {
            document.getElementById(id).remove();
        }
    </script>
    [{else}]
    [{$smarty.block.parent}]
    [{/if}]
