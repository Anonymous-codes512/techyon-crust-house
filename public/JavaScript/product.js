
function addProduct() {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('newProduct');

    overlay.style.display = 'block';
    popup.style.display = 'flex';
}

function closeAddProduct() {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('newProduct');

    overlay.style.display = 'none';
    popup.style.display = 'none';
}

function editProduct(product) {
    const overlay = document.getElementById('editOverlay');
    const popup = document.getElementById('editProduct');

    document.getElementById('pId').value = product.id;
    document.getElementById('pName').value = product.productName;
    document.getElementById('editProductVariation').value = product.productVariation;
    document.getElementById('editVariationPrice').value = product.productPrice;


    overlay.style.display = 'block';
    popup.style.display = 'flex';
}

function closeEditCatogry() {
    let overlay = document.getElementById('editOverlay');
    let popup = document.getElementById('editProduct');

    overlay.style.display = 'none';
    popup.style.display = 'none';
}

function createInputField(labelText, inputType, inputName, inputPlaceholder) {
    const label = document.createElement('label');
    label.textContent = labelText;

    const input = document.createElement('input');
    input.type = inputType;
    input.name = inputName;
    input.placeholder = inputPlaceholder;
    input.required = true;

    return { label, input };
}

function updateVariationFields(number) {
    const variationsGroup = document.getElementById('variationsGroup');
    variationsGroup.innerHTML = '';
    const count = parseInt(number) || 0;

    for (let i = 0; i < count; i++) {
        const variationsDiv = document.createElement('div');
        variationsDiv.className = 'variation-item';

        const productVariationDiv = document.createElement('div');
        productVariationDiv.className = 'product-variation';

        const productPriceDiv = document.createElement('div');
        productPriceDiv.className = 'product-price';

        const productVariation = createInputField(
            `Product Variation ${i + 1}`,
            'text',
            `productVariation${i + 1}`,
            `Product Variation ${i + 1}`
        );

        const priceVariation = createInputField(
            `Price ${i + 1}`,
            'number',
            `price${i + 1}`,
            `Price ${i + 1}`
        );

        productVariationDiv.appendChild(productVariation.label);
        productVariationDiv.appendChild(productVariation.input);

        productPriceDiv.appendChild(priceVariation.label);
        productPriceDiv.appendChild(priceVariation.input);

        variationsDiv.appendChild(productVariationDiv);
        variationsDiv.appendChild(productPriceDiv);
        
        variationsGroup.appendChild(variationsDiv);
    }
}

const uploadUpdatedFile = document.getElementById('upload-update-file');
const filenamSpan = document.getElementById('namefile');
uploadUpdatedFile.addEventListener('change', function(e) {
    const fileNam = this.value.split('\\').pop();
    filenamSpan.textContent = fileNam ? fileNam : 'No file chosen';
});

const uploadFile = document.getElementById('upload-file');
const filenameSpan = document.getElementById('filename');
uploadFile.addEventListener('change', function(e) {
    const fileName = this.value.split('\\').pop();
    filenameSpan.textContent = fileName ? fileName : 'No file chosen';
});
