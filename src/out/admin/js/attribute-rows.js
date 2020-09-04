const optionTemplate = '<option value="KEY_PLACEHOLDER">ATTRIBUTE_PLACEHOLDER</option>';
const baseTemplate = '<div class="attributes_wrapper">' +
    '<table id="attributes" style="width:300px"><thead><tr><th>Name</th><th>Multi-Attribute</th> <th>Action</th> </tr>' +
    ' </thead> <tbody> </tbody> </table> <input type="button" onclick="addNewRow()" value="Add"/> </div>';

const rowTemplate = '<tr class="attribute-row" id="ID_PLACEHOLDER"><td><select id="ATTRIBUTE_CODE_PLACEHOLDER-ID_PLACEHOLDER" class="attribute-select"  name="confaarrs[ATTRIBUTE_CODE_PLACEHOLDER][ID_PLACEHOLDER][id]">' +
    'OPTIONS_PLACEHOLDER</select></td><td><select id="type-ATTRIBUTE_CODE_PLACEHOLDER-ID_PLACEHOLDER" class="separate-column-select" name="confaarrs[ATTRIBUTE_CODE_PLACEHOLDER][ID_PLACEHOLDER][multi]"><option value="0">No</option><option value="1">Yes</option></select>' +
    '</td><td><input type="button" onclick="deleteRow(\'ID_PLACEHOLDER\')" class="row-remove"/></td></tr>';

function AttributeRows() {
    const element = HTMLElement.call(this);
    element.rowTemplate = rowTemplate;
    element.insertAdjacentHTML('beforeend', baseTemplate);
    return element;
}

AttributeRows.prototype = Object.create(HTMLElement.prototype);
AttributeRows.prototype.constructor = AttributeRows;

AttributeRows.observedAttributes = ['selected-attributes', 'available-attributes'];
AttributeRows.prototype.createRow = createRow;
AttributeRows.prototype.connectedCallback = function () {
    this.selectedAttributes = undefined;
    this.availableAttributes = undefined;
};

AttributeRows.prototype.attributeChangedCallback = function (name, oldValue, newValue) {
    if (name === 'selected-attributes' && newValue !== undefined) {
        this.selectedAttributes = JSON.parse(newValue);
    }

    if (name === 'available-attributes' && newValue !== undefined) {
        this.availableAttributes = JSON.parse(newValue);
    }

    const availableOptions = [];
    if (this.availableAttributes) {
        for (var key of Object.keys(this.availableAttributes)) {
            availableOptions.push(optionTemplate.replace('KEY_PLACEHOLDER', key).replace('ATTRIBUTE_PLACEHOLDER', this.availableAttributes[key]));
        }
    }
    if (availableOptions.length > 0) {
        this.rowTemplate = this.rowTemplate.replace('OPTIONS_PLACEHOLDER', availableOptions.join(''));

        const attributeRows = document.querySelector('#attributes tbody');
        while (attributeRows.lastElementChild) {
            attributeRows.removeChild(attributeRows.lastElementChild);
        }

        for (var id in this.selectedAttributes) {
            this.createRow(id, this.selectedAttributes[id]);
        }
    }
};

customElements.define('attribute-rows', AttributeRows);

function createRow(elementId, selectedAttribute = false) {
    const preparedTemplate = this.rowTemplate.replace(new RegExp('ATTRIBUTE_CODE_PLACEHOLDER', 'g'), 'ffExportAttributes').replace(new RegExp('ID_PLACEHOLDER', 'g'), elementId);
    const element = document.createElement('tr');
    element.id = elementId;
    element.classList.add('attribute-row');
    element.innerHTML = preparedTemplate;

    if (selectedAttribute !== false) {
        const attrOption = element.querySelector('option[value="' + selectedAttribute.id + '"]');
        if (attrOption) {
            attrOption.setAttribute('selected', 'selected');
        }

        const multiOption = element.querySelector('.separate-column-select option[value="' + selectedAttribute.multi + '"]');
        multiOption.setAttribute('selected', 'selected');
    }

    document.querySelector('#attributes tbody').appendChild(element);
}

function addNewRow() {
    document.querySelector('attribute-rows').createRow(document.querySelectorAll('#attributes tr').length);
}

function deleteRow(id) {
    document.getElementById(id).remove();
}
