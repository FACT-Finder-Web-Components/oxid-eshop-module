const optionTemplate = '<option value="KEY_PLACEHOLDER">ATTRIBUTE_PLACEHOLDER</option>';
const baseTemplate = '<div class="attributes_wrapper">' +
    '<table id="attributes" style="width:300px"><thead><tr><th>Action</th> </tr>' +
    ' </thead> <tbody> </tbody> </table> <input type="button" onclick="addNewRow()" value="Add"/> </div>';

const rowTemplate = '<tr class="attribute-row" id="ID_PLACEHOLDER"><td><select id="ATTRIBUTE_CODE_PLACEHOLDER-ID_PLACEHOLDER" class="attribute-select"  name="confaarrs[ATTRIBUTE_CODE_PLACEHOLDER][ID_PLACEHOLDER][id]">' +
    'OPTIONS_PLACEHOLDER</select></td><td><select id="type-ATTRIBUTE_CODE_PLACEHOLDER-ID_PLACEHOLDER" class="separate-column-select" name="confaarrs[ATTRIBUTE_CODE_PLACEHOLDER][ID_PLACEHOLDER][multi]"><option value="0">No</option><option value="1">Yes</option></select>' +
    '</td><td><input type="button" onclick="deleteRow(\'ID_PLACEHOLDER\')" class="row-remove"/></td></tr>';

function AttributeRows() {
    const element = HTMLElement.call(this);
    element.selectedAttributes = undefined;
    element.availableAttributes = [];
    element.headers = [];
    element.insertAdjacentHTML('beforeend', baseTemplate);
    return element;
}

AttributeRows.prototype = Object.create(HTMLElement.prototype);
AttributeRows.prototype.constructor = AttributeRows;
AttributeRows.observedAttributes = ['selected-attributes', 'available-attributes', 'headers'];
AttributeRows.prototype.createRow = createRow;

AttributeRows.prototype.attributeChangedCallback = function (name, oldValue, newValue) {
    const isJson = function (str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    };
    switch (name) {
        case 'selected-attributes':
            if (isJson(newValue)) {
                this.selectedAttributes = JSON.parse(newValue);
            } else {
                this.selectedAttributes = undefined;
            }
            break;
        case 'available-attributes':
            if (isJson(newValue)) {
                const parsedAttributes = JSON.parse(newValue);
                const replace = function (key) {
                    this.availableAttributes.push(optionTemplate
                        .replace('KEY_PLACEHOLDER', key)
                        .replace('ATTRIBUTE_PLACEHOLDER', parsedAttributes[key]),
                    );
                };
                if (parsedAttributes) {
                    Object.keys(parsedAttributes).map(replace.bind(this));
                    this.rowTemplate = rowTemplate.replace('OPTIONS_PLACEHOLDER', this.availableAttributes.join(''));
                }
            } else {
                this.availableAttributes = [];
                if (newValue !== oldValue) console.error('No available attributes specified');
            }
            break;
        case 'headers':
            this.headers = newValue.split(',').concat('Action');
            break;
    }

    const headerElement = document.querySelector('#attributes thead tr');
    while (headerElement.lastElementChild) {
        headerElement.removeChild(headerElement.lastElementChild);
    }

    const attributeRows = document.querySelector('#attributes tbody');
    while (attributeRows.lastElementChild) {
        attributeRows.removeChild(attributeRows.lastElementChild);
    }

    this.headers.forEach(function (header) {
        const newHeader = document.createElement('th');
        newHeader.innerText = header;
        headerElement.appendChild(newHeader);
    });

    if (this.availableAttributes.length > 0) {
        const createRow = function (attributeId) {
            this.createRow(attributeId, this.selectedAttributes[attributeId]);
        };
        Object.keys(this.selectedAttributes).forEach(createRow.bind(this));
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
