function showAddToCart(product, deals, allProducts) {
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('addToCart');

    if (!overlay || !popup) return;

    const drinkFlavour = document.getElementById('drinkFlavour');
    const prodName = document.getElementById('prodName');
    const productId = document.getElementById('product_id');
    const price = document.getElementById('price');
    const totalPrice = document.getElementById('totalprice');
    const prodVariation = document.getElementById('prodVariation');
    const addons = document.getElementById('addons');
    const addOnsLabel = document.getElementById('addOnsLabel');
    const prodVariationLabel = document.getElementById('prodVariationLabel');

    if (product.category_name) {
        if (drinkFlavour) drinkFlavour.style.display = 'none';
        if (productId) productId.value = product.id;
        if (prodName) prodName.textContent = `${product.productVariation} ${product.productName}`;
        if (price) price.textContent = `Rs. ${product.productPrice}`;
        if (totalPrice) totalPrice.textContent = `Rs. ${product.productPrice}`;

        if (product.category_name.toLowerCase() != 'others') {
            if (prodVariation) prodVariation.style.display = 'block';
            if (addons) addons.style.display = 'block';
            updateProductSizeDropdown(product, allProducts);
        } else {
            if (prodName) prodName.textContent = product.productName;
            if (addons) addons.style.display = 'none';
            if (addOnsLabel) addOnsLabel.style.display = 'none';
            if (prodVariation) prodVariation.style.display = 'none';
            if (prodVariationLabel) prodVariationLabel.style.display = 'none';
        }
    } else {
        if (prodName) prodName.textContent = product.deal.dealTitle;
        if (price) price.textContent = `Rs. ${product.deal.dealDiscountedPrice}`;
        if (totalPrice) totalPrice.textContent = `Rs. ${product.deal.dealDiscountedPrice}`;
        if (drinkFlavour) drinkFlavour.style.display = 'block';
        updateDealsDropdown(product, deals, allProducts);
    }

    overlay.style.display = 'block';
    popup.style.display = 'flex';
}

/* 
|---------------------------------------------------------------|
|================ Deal's DropdownFunctions =====================|
|---------------------------------------------------------------|
*/

function updateDealsDropdown(deal, deals, allProducts) {
    const pizzaFlavourDropdown = document.getElementById("addons");
    const addOnsDropdown = document.getElementById("drinkFlavour");
    const drinkFlavourDropdown = document.getElementById("prodVariation");

    clearDropdown(pizzaFlavourDropdown);
    clearDropdown(drinkFlavourDropdown);
    clearDropdown(addOnsDropdown);

    let addOnsArray = [];
    let pizzaFlavour = [];
    let drinkFlavour = [];
    let dealProductName = [];
    let dealProductVariations = [];

    deals.forEach(element => {
        if (element.deal_id === deal.deal_id) {
            const product = element.product;
            if (product) {
                dealProductName.push(product.productName);
                dealProductVariations.push(product.productVariation);
            } else {
                console.warn("Deal", deal.id, "has no associated product");
            }
        }
    });

    allProducts.forEach(element => {
        if (element.category_name.toLowerCase() === 'addons' && element.productName.includes('Topping')) {
            addOnsArray.push(`${element.productVariation} (Rs. ${element.productPrice})`);
        }
    });

    let pizza_name;
    for (let i = 0; i < dealProductName.length; i++) {
        let found = false;
        allProducts.forEach(element => {
            if (element.category_name.toLowerCase() === 'pizza' && element.productVariation === dealProductVariations[i]) {
                pizza_name = dealProductName[i];
                pizzaFlavour.push(element.productName);
                found = true;
            }
        });
        if (found) break;
    }

    let drink_name;
    let variation;
    for (let i = 0; i < dealProductName.length; i++) {
        let found = false;

        allProducts.forEach(element => {
            if (element.category_name.toLowerCase() === 'drinks') {
                if (element.productName === dealProductName[i]) {
                    variation = dealProductVariations[i];
                }

                if (element.productVariation === variation) {
                    drink_name = dealProductName[i];
                    drinkFlavour.push(element.productName);
                    found = true;
                }
            }
        });

        if (found) break;
    }

    PizzaFlavourDropdown(pizzaFlavour, pizzaFlavourDropdown, pizza_name);
    DrinkFlavourDropdown(drinkFlavour, drinkFlavourDropdown, drink_name);
    addOnsDealDropdown(addOnsArray, addOnsDropdown);
}

function clearDropdown(dropdown) {
    if (dropdown) dropdown.innerHTML = "";
}

function PizzaFlavourDropdown(optionsArray, dropdown, name) {
    if (!dropdown) return;
    dropdown.innerHTML = "";
    const label = document.getElementById("addOnsLabel");
    if (label) label.textContent = "Select Pizza Flavour";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'Default';
    defaultOption.value = name;
    dropdown.add(defaultOption);

    optionsArray.forEach(optionText => {
        let option = document.createElement("option");
        option.text = optionText;
        option.value = optionText;
        dropdown.add(option);
    });
}

function DrinkFlavourDropdown(optionsArray, dropdown, name) {
    if (!dropdown) return;
    const label = document.getElementById("prodVariationLabel");

    if (dropdown) dropdown.style.display = 'block';
    if (label) label.style.display = 'block';

    if (!name) {
        if (dropdown) dropdown.style.display = 'none';
        if (label) label.style.display = 'none';
        return;
    }

    dropdown.innerHTML = "";
    if (label) label.textContent = "Select Drink Flavour";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'Default';
    defaultOption.value = name;
    dropdown.add(defaultOption);

    optionsArray.forEach(optionText => {
        let option = document.createElement("option");
        option.text = optionText;
        option.value = optionText;
        dropdown.add(option);
    });
}

function addOnsDealDropdown(optionsArray, dropdown) {
    if (!dropdown) return;
    dropdown.innerHTML = "";
    const label = document.getElementById("drinkFlavourLabel");
    if (label) label.textContent = "Select Extra Topping";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'None';
    defaultOption.value = '';
    dropdown.add(defaultOption);

    optionsArray.forEach(optionText => {
        let option = document.createElement("option");
        option.text = optionText;
        option.value = optionText;
        dropdown.add(option);
    });

    dropdown.addEventListener('change', () => {
        const quantityElement = document.getElementById('prodQuantity');
        const totalPriceElement = document.getElementById('totalprice');
        const priceElement = document.getElementById('price');

        if (quantityElement) quantityElement.value = '1';

        let price = parseFloat(priceElement.textContent.replace('Rs. ', '')) || 0;
        let selectedOption = dropdown.options[dropdown.selectedIndex];
        let addonPrice = parseFloat((selectedOption.value.match(/Rs\. (\d+)/) || [0, 0])[1]) || 0;

        const orderPrice = price + addonPrice;
        if (totalPriceElement) totalPriceElement.textContent = 'Rs. ' + orderPrice;
        if (priceElement) priceElement.textContent = 'Rs. ' + orderPrice;
    });
}

/*
|---------------------------------------------------------------|
|================ others Dropdown Functions ====================|
|---------------------------------------------------------------|
*/

function updateProductSizeDropdown(product, allProducts) {
    let productVariationDropdown = document.getElementById("prodVariation");
    let drinkFlavourDropdown = document.getElementById("addons");
    let addOnsLabel = document.getElementById('addOnsLabel');
    let prodVariationLabel = document.getElementById("prodVariationLabel");

    productVariationDropdown.innerHTML = "";
    drinkFlavourDropdown.innerHTML = "";
    addOnsLabel.style.display = 'none';
    prodVariationLabel.style.display = 'none';

    if (product.category_name.toLowerCase() === "pizza") {
        handlePizzaCategory(product, allProducts, productVariationDropdown, drinkFlavourDropdown);
    } else if (product.category_name.toLowerCase() === "drinks") {
        handleDrinksCategory(product, allProducts, productVariationDropdown, drinkFlavourDropdown);
    } else {
        handleOtherCategories(product, allProducts, productVariationDropdown, drinkFlavourDropdown);
    }
}

function handlePizzaCategory(product, allProducts, productVariationDropdown, drinkFlavourDropdown) {
    let productVariations = [];
    let addOnsArray = [];

    document.getElementById("prodVariationLabel").style.display = 'block';

    allProducts.forEach(element => {
        if (element.category_name.toLowerCase() === 'addons' && element.productName.includes('Topping')) {
            addOnsArray.push(`${element.productVariation} (Rs. ${element.productPrice})`);
        }
        if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
            productVariations.push(`${element.productVariation} (Rs. ${element.productPrice})`);
        }
    });

    addOnsDropdown(addOnsArray, drinkFlavourDropdown, 'Extra Topping');
    addOptionsToDropdown(productVariations, productVariationDropdown);
}

function handleDrinksCategory(product, allProducts, productVariationDropdown, drinkFlavourDropdown) {
    let productVariations = [];
    let uniqueDrinkFlavours = new Set();

    document.getElementById('addOnsLabel').style.display = 'block';
    document.getElementById("prodVariationLabel").style.display = 'block';

    allProducts.forEach(element => {
        if (element.category_name.toLowerCase() === 'drinks') {
            uniqueDrinkFlavours.add(element.productName);
        }
        if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
            productVariations.push(`${element.productVariation} (Rs. ${element.productPrice})`);
        }
    });

    let drinkFlavour = Array.from(uniqueDrinkFlavours);
    addOnsDropdown(drinkFlavour, drinkFlavourDropdown, 'Drink Flavour');
    addOptionsToDropdown(productVariations, productVariationDropdown);

    drinkFlavourDropdown.addEventListener('change', () => {
        let selectedFlavour = drinkFlavourDropdown.value;
        let filteredVariations = allProducts.filter(product => product.category_name.toLowerCase() === "drinks" && product.productName === selectedFlavour);

        if (filteredVariations.length > 0) {
            let selectedProduct = filteredVariations[0];
            let variationOptions = filteredVariations.map(product => `${product.productVariation} (Rs. ${product.productPrice})`);
            document.getElementById('price').textContent = 'Rs. ' + selectedProduct.productPrice;
            document.getElementById('totalprice').textContent = 'Rs. ' + selectedProduct.productPrice;
            addOptionsToDropdown(variationOptions, productVariationDropdown);
        }
    });
}

function handleOtherCategories(product, allProducts, productVariationDropdown, drinkFlavourDropdown) {
    let productVariations = [];
    document.getElementById('addons').style.display = 'none';
    allProducts.forEach(element => {
        if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
            productVariations.push(`${element.productVariation} (Rs. ${element.productPrice})`);
        }
    });

    addOptionsToDropdown(productVariations, productVariationDropdown);
}

function addOptionsToDropdown(optionsArray, dropdown) {
    dropdown.innerHTML = "";
    let label = document.getElementById("prodVariationLabel");
    label.textContent = "Select Variation";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'Default';
    defaultOption.value = optionsArray[0];
    dropdown.add(defaultOption);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }

    dropdown.addEventListener('change', () => {
        document.getElementById('prodQuantity').value = '1';
        let selectedOption = dropdown.options[dropdown.selectedIndex];

        let totalPriceElement = document.getElementById('totalprice');
        let match = selectedOption.value.match(/Rs\. (\d+)/);
        let price = match ? match[1] : 0;

        let addonsoption = document.getElementById('addons');
        let price1 = 0;
        if (addonsoption.value != '') {
            let match1 = addonsoption.value.match(/Rs\. (\d+)/);
            price1 = match1 ? match1[1] : 0;
        }

        const order_price = parseFloat(price) + parseFloat(price1);
        totalPriceElement.textContent = 'Rs. ' + order_price;
        document.getElementById('price').textContent = 'Rs. ' + order_price;
    });
}

function addOnsDropdown(optionsArray, dropdown, labeltext) {
    dropdown.innerHTML = "";

    let label = document.getElementById("addOnsLabel");
    label.style.display = 'block';
    label.textContent = labeltext;

    let defaultOption = document.createElement("option");
    defaultOption.text = `Select ${labeltext}`;
    defaultOption.value = '';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    dropdown.add(defaultOption);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }

    dropdown.addEventListener('change', () => {
        document.getElementById('prodQuantity').value = '1';
        let selectedOption = dropdown.options[dropdown.selectedIndex];

        let totalPriceElement = document.getElementById('totalprice');
        let match = selectedOption.value.match(/Rs\. (\d+)/);
        let price = match ? match[1] : 0;

        if (selectedOption.value == '') {
            price = 0;
        }

        let variationprice = document.getElementById('prodVariation');
        let match1 = variationprice.value.match(/Rs\. (\d+)/);
        let price1 = match1 ? match1[1] : 0;

        const order_price = parseFloat(price) + parseFloat(price1);
        totalPriceElement.textContent = 'Rs. ' + order_price;
        document.getElementById('price').textContent = 'Rs. ' + order_price;
    });
}

/*
|---------------------------------------------------------------|
|==================== Add to Cart Functions ====================|
|---------------------------------------------------------------|
*/

let allAddedProducts = [];
let index = 1;

function add(allProducts) {
    let productName = document.getElementById('prodName').textContent.trim();
    let product = productName.split(" ");
    let prod = product[0];
    productName = productName.replace(prod, "");

    let productVariation = document.getElementById('prodVariation').value;
    let addOns = document.getElementById('addons').value;
    let productPrice = parseFloat(document.getElementById('totalprice').textContent.replace('Rs. ', ''));
    let quantity = document.getElementById('prodQuantity').value;

    let extractedText;

    let pTag = document.createElement('p');
    pTag.style.borderBottom = '1px solid #000';
    pTag.id = 'order' + index;

    let textarea = document.createElement('textarea');
    textarea.readOnly = true;
    textarea.style.resize = 'none';
    textarea.rows = '3';
    textarea.cols = '4';
    textarea.style.width = "95%";
    textarea.style.height = "auto";
    textarea.style.border = 'none';

    let divQuantity = document.createElement('div');
    divQuantity.style.display = "flex";
    divQuantity.style.alignItems = "center";
    divQuantity.style.marginBottom = "5px";

    let quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.id = 'OrderQuantity' + index;
    quantityInput.name = 'OrderQuantity' + index;
    quantityInput.style.width = '30px';
    quantityInput.style.textAlign = 'center';
    quantityInput.value = quantity;

    let increaseIcon = document.createElement('i');
    increaseIcon.style.fontSize = '2vw';
    increaseIcon.style.color = '#d40000';
    increaseIcon.className = 'bx bxs-plus-square';

    let decreaseIcon = document.createElement('i');
    decreaseIcon.style.fontSize = '2.5vw';
    decreaseIcon.style.color = '#d40000';
    decreaseIcon.className = 'bx bxs-checkbox-minus';

    divQuantity.appendChild(decreaseIcon);
    divQuantity.appendChild(quantityInput);
    divQuantity.appendChild(increaseIcon);

    pTag.appendChild(textarea);
    pTag.appendChild(divQuantity);

    document.getElementById('selectedProducts').appendChild(pTag);

    if (!addOns) {
        let productDetails = productVariation.replace(/\s+/g, '') + productName;
        extractedText = productDetails.replace(/\(.*?\)/, '');
        extractedText = extractedText.trim();

    } else {
        let productDetails = productVariation.replace(/\s+/g, '') + productName + ' with extra ' + addOns.replace(/\s*\(Rs\.\s*\d+\)\s*/, "");
        allProducts.forEach(element => {
            if (element.productName == addOns) {
                if (element.category_name.toLowerCase() == 'drinks') {
                    productName = addOns;
                    productDetails = productVariation.replace(/\s+/g, '') + ' ' + addOns;
                }
            }
        });
        extractedText = productDetails.replace(/\(.*?\)/, '');
        extractedText = extractedText.trim();
    }

    textarea.textContent = productName.replace(/^ /, "") + '\n' + extractedText;
    extractedText = '';

    let totalSpan = document.createElement('span');
    totalSpan.style.marginLeft = '3rem';
    totalSpan.id = 'orderItemPrice' + index;
    totalSpan.style.fontSize = '0.8rem';
    totalSpan.textContent = 'Total: Rs. ' + productPrice.toFixed(2);
    divQuantity.appendChild(totalSpan);

    let totalBillString = document.getElementById('totalbill').value;
    let totalBillValue;

    if (totalBillString.startsWith("Total Bill:")) {
        totalBillValue = parseFloat(totalBillString.split("Rs. ")[1]);
    } else {
        totalBillValue = parseFloat(totalBillString);
    }

    let variationName = (productVariation && productVariation.match(/^[^\(]+/)) ? productVariation.match(/^[^\(]+/)[0].trim() : '';
    let productObj = {
        name: productName,
        variation: variationName,
        addons: addOns.replace(/\s*\(Rs\.\s*\d+\)\s*/, ""),
        price: productPrice,
        quantity: quantityInput.value.replace(/\s+/g, ' ')
    };

    allAddedProducts.push(productObj);
    let hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.id = 'hidden-field ' + index;
    hiddenInput.name = 'product' + index;
    hiddenInput.value = JSON.stringify(productObj);
    document.getElementById('cart').appendChild(hiddenInput);

    let productId = generateProductId();

    let currentTotal = totalBillValue + productPrice;
    document.getElementById('totalbill').value = "Total Bill:\t\t Rs. " + currentTotal.toFixed(2);
    index++;

    increaseIcon.setAttribute('onclick', `increaseWhole('${productId}', '${quantityInput.id}', '${totalSpan.id}', '${hiddenInput.id}')`);
    decreaseIcon.setAttribute('onclick', `decreaseWhole('${pTag.id}', '${productId}', '${quantityInput.id}', '${totalSpan.id}', '${hiddenInput.id}')`);

    closeAddToCart();

    document.getElementById('prodVariation').value = '';
    document.getElementById('addons').value = '';
    document.getElementById('prodQuantity').value = '1';

    sessionStorage.setItem('selectedProducts', JSON.stringify(allAddedProducts));
}

function generateProductId() {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const length = 8;
    let productId = '';
    for (let i = 0; i < length; i++) {
        productId += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return productId;
}

/*
|---------------------------------------------------------------|
|============== Increment Decrement Functions ==================|
|---------------------------------------------------------------|
*/

function increase() {
    let quantityInput = document.getElementById('prodQuantity');
    let currentValue = parseInt(quantityInput.value);
    currentValue = currentValue + 1;
    quantityInput.value = currentValue;

    let productPriceElement = document.getElementById('price');
    let numericValue = productPriceElement.textContent.match(/\d+(\.\d+)?/);
    let totalPrice = parseFloat(numericValue[0]);
    totalPrice = totalPrice * currentValue;

    let totalPriceElement = document.getElementById('totalprice');
    totalPriceElement.textContent = "Rs. " + totalPrice.toFixed(2);
}

function decrease() {
    let quantityInput = document.getElementById('prodQuantity');
    let currentValue = parseInt(quantityInput.value);

    if (currentValue <= 1) {
        alert('Minimum quantity should be 1.');
    } else {
        currentValue = currentValue - 1;
        quantityInput.value = currentValue;

        let productPriceElement = document.getElementById('price');
        let numericValue = productPriceElement.textContent.match(/\d+(\.\d+)?/);
        let unitPrice = parseFloat(numericValue[0]);

        let totalPrice = unitPrice * currentValue;

        let totalPriceElement = document.getElementById('totalprice');
        totalPriceElement.textContent = "Rs. " + totalPrice.toFixed(2);
    }
}

let initialTotalPrices = {};
let TotalOrderPrices = {};
let product_details = {};

function updateTotalOrderPricesSum() {
    let totalOrderPricesSum = Object.values(TotalOrderPrices).reduce((acc, curr) => acc + curr, 0);
    let orderPrice = document.getElementById('totalbill');
    orderPrice.value = "Total Bill:\t\t Rs. " + totalOrderPricesSum.toFixed(2);
    console.log("Updated total bill: ", totalOrderPricesSum);
}

function increaseWhole(productId, quantityInputId, totalSpanId, hiddenInputId) {
    let quantityInputElement = document.getElementById(quantityInputId);
    let totalSpanElement = document.getElementById(totalSpanId);
    let hiddenInputElement = document.getElementById(hiddenInputId);
    let parsedData = JSON.parse(hiddenInputElement.value);
    let currentOrderItemQuantity = parseInt(quantityInputElement.value);

    if (!(productId in initialTotalPrices)) {
        let pattern = /\d+\.\d+/;
        let match = totalSpanElement.textContent.match(pattern);
        let initialPrice = match ? parseFloat(match[0]) : 0;
        initialTotalPrices[productId] = initialPrice / currentOrderItemQuantity;
        product_details[productId] = parsedData;
        console.log("Initial setup for ", productId, ": ", initialTotalPrices[productId], " per unit.");
    }

    let newOrderItemQuantity = currentOrderItemQuantity + 1;
    product_details[productId].quantity = newOrderItemQuantity;
    quantityInputElement.value = newOrderItemQuantity;

    let totalPrice = initialTotalPrices[productId] * newOrderItemQuantity;
    product_details[productId].price = totalPrice;
    hiddenInputElement.value = JSON.stringify(product_details[productId]);
    totalSpanElement.textContent = "Total: Rs. " + totalPrice.toFixed(2);

    TotalOrderPrices[productId] = totalPrice;

    updateTotalOrderPricesSum();
}

function decreaseWhole(OrderElementId, productId, quantityInputId, totalSpanId, hiddenInputId) {
    let quantityInputElement = document.getElementById(quantityInputId);
    let totalSpanElement = document.getElementById(totalSpanId);
    let hiddenInputElement = document.getElementById(hiddenInputId);
    let parsedData = JSON.parse(hiddenInputElement.value);
    let currentOrderItemQuantity = parseInt(quantityInputElement.value);

    if (!(productId in initialTotalPrices)) {
        return;
    }

    if (currentOrderItemQuantity > 1) {
        let newOrderItemQuantity = currentOrderItemQuantity - 1;
        product_details[productId].quantity = newOrderItemQuantity;
        quantityInputElement.value = newOrderItemQuantity;

        let totalPrice = initialTotalPrices[productId] * newOrderItemQuantity;
        product_details[productId].price = totalPrice;
        hiddenInputElement.value = JSON.stringify(product_details[productId]);
        totalSpanElement.textContent = "Total: Rs. " + totalPrice.toFixed(2);

        TotalOrderPrices[productId] = totalPrice;
        updateTotalOrderPricesSum();
    } else {
        let response = confirm('Are you sure you want to remove the product?');
        if (response) {
            let OrderElement = document.getElementById(OrderElementId);
            OrderElement.parentNode.removeChild(OrderElement);
            hiddenInputElement.remove();

            delete initialTotalPrices[productId];
            delete product_details[productId];
            delete TotalOrderPrices[productId];

            updateTotalOrderPricesSum();

            console.log("Removed product ", productId);
        } else {
            alert('Minimum quantity should be 1.');
        }
    }
}

/*
|---------------------------------------------------------------|
|================ Print and Proceed Functions ==================|
|---------------------------------------------------------------|
*/

function printReceipt() {
    let heading = document.getElementById('heading').cloneNode(true);
    let selectedProducts = document.getElementById('selectedProducts').cloneNode(true);
    let totalbill = document.getElementById('totalbill').cloneNode(true);
    totalbill.style.fontSize = '3vw';
    document.body.querySelectorAll('*').forEach(element => {
        if ((element.id !== 'heading') && (element.id !== 'selectedProducts') && (element.id !== 'totalbill')) {
            element.style.display = 'none';
        }
    });
    document.body.appendChild(heading);
    document.body.appendChild(selectedProducts);
    document.body.appendChild(totalbill);
    window.print();
    heading.remove();
    selectedProducts.remove();
    totalbill.remove();
    document.body.querySelectorAll('*').forEach(element => {
        element.style.display = '';
    });
}

function closeAddToCart() {
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('addToCart');
    const quantityElement = document.getElementById('prodQuantity');
    if (quantityElement) quantityElement.value = '1';
    if (overlay) overlay.style.display = 'none';
    if (popup) popup.style.display = 'none';
}

// let reloadingPage = false;
// window.addEventListener('beforeunload', function (event) {
//     if (!reloadingPage) {
//         let selectedProductsHTML = document.getElementById('selectedProducts').innerHTML;
//         let totalBillValue = document.getElementById('totalbill').textContent;
//         localStorage.setItem('selectedProductsHTML', selectedProductsHTML);
//         localStorage.setItem('totalBillValue', totalBillValue);
//     }
// });
// window.addEventListener('DOMContentLoaded', function (event) {
//     let savedSelectedProductsHTML = localStorage.getItem('selectedProductsHTML');
//     let savedTotalBillValue = localStorage.getItem('totalBillValue');
    
//     if (savedSelectedProductsHTML) {
//         document.getElementById('selectedProducts').innerHTML = savedSelectedProductsHTML;
//     }
    
//     if (savedTotalBillValue) {
//         document.getElementById('totalbill').textContent = savedTotalBillValue;
//     }
// });

// // Handle Ctrl+R or Ctrl+Shift+R to clear selected products and total bill
// window.addEventListener('keydown', function (event) {
//     if ((event.ctrlKey && event.shiftKey && event.keyCode === 82) || (event.ctrlKey && event.keyCode === 82)) {
//         reloadingPage = true;
//         localStorage.removeItem('selectedProductsHTML');
//         localStorage.removeItem('totalBillValue');
//     }
// });

