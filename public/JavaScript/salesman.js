function showAddToCart(product, allProducts) {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('addToCart');
    document.getElementById('drinkFlavour').style.display = 'none'

    if (product.category_name) {

        let name = document.getElementById('prodName');
        name.textContent = product.productSize + " " + product.productName;
        document.getElementById('price').textContent = 'Rs. ' + product.productPrice;
        document.getElementById('totalprice').textContent = 'Rs. ' + product.productPrice;

        if (product.category_name.toLowerCase() != 'others') {
            document.getElementById("prodVariation").style.display = 'block';
            document.getElementById('addons').style.display = 'block';
            updateProductSizeDropdown(product, allProducts);

        } else {

            let name = document.getElementById("prodName");
            name.textContent = product.productName;
            document.getElementById('addons').style.display = 'none';
            document.getElementById('addOnsLabel').style.display = 'none'
            document.getElementById("prodVariation").style.display = 'none';
            document.getElementById('prodVariationLabel').style.display = 'none'
        }

    } else {

        let productname = product.dealProductName.split(',');
        let productquantity = product.dealProductQuantity.split(',');
        let productvariation = product.dealProductVariation.split(',');
        let deal_products = [];

        for (let i = 0; i < productname.length; i++) {
            if (productname[i].includes(productvariation[i])) {
                deal_products.push(productquantity[i] + " " + productname[i]);
            } else {
                deal_products.push(productquantity[i] + " " + productvariation[i] + " " + productname[i]);

            }
        }

        const uniqueItems = [...new Set(deal_products)];
        const order = uniqueItems.join(', ');

        document.getElementById('prodName').textContent = order;
        document.getElementById('price').textContent = 'Rs. ' + product.dealDiscountedPrice;
        document.getElementById('totalprice').textContent = 'Rs. ' + product.dealDiscountedPrice;
        document.getElementById('drinkFlavour').style.display = 'block'

        // document.getElementById('addbtn').setAttribute('onclick', 'add(' + JSON.stringify(product) + ', ' + JSON.stringify(allProducts) + ')');

        updateDealsDropdown(product, allProducts);

    }

    overlay.style.display = 'block';
    popup.style.display = 'flex';
}

function closeAddToCart() {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('addToCart');

    document.getElementById('prodQuantity').value = '1';

    overlay.style.display = 'none';
    popup.style.display = 'none';
}

/*
|---------------------------------------------------------------|
|================ Deal's DropdownFunctions =====================|
|---------------------------------------------------------------|
*/

function updateDealsDropdown(deals, allProducts) {
    let pizzaFlavourDropdown = document.getElementById("addons");
    let addOnsDropdown = document.getElementById("drinkFlavour");
    let drinkFlavourDropdown = document.getElementById("prodVariation");

    pizzaFlavourDropdown.innerHTML = "";
    drinkFlavourDropdown.innerHTML = "";
    addOnsDropdown.innerHTML = "";

    let addOnsArray = [];
    let pizzaFlavour = [];
    let drinkFlavour = [];

    let dealProducts = deals.dealProductName.split(',');
    let dealProductVariations = deals.dealProductVariation.split(',');

    allProducts.forEach(element => {
        if (element.category_name.toLowerCase() == 'addons') {
            if (element.productName.includes('Topping')) {
                addOnsArray.push(`${element.productName} (Rs. ${element.productPrice})`);
            }
        }
    })

    let pizza_name;

    for (let i = 0; i < dealProducts.length; i++) {
        allProducts.forEach(element => {
            if (element.category_name.toLowerCase() === 'pizza') {
                if (element.productSize === dealProductVariations[i]) {
                    pizza_name = dealProducts[i];
                    pizzaFlavour.push(`${element.productName}`);
                }
            }
        });
        break;
    }

    let drink_name;
    for (let i = 0; i < dealProducts.length; i++) {
        let found = false;
        allProducts.forEach(element => {
            if (element.category_name.toLowerCase() === 'drinks' && element.productSize === dealProductVariations[i]) {
                drink_name = dealProducts[i];
                drinkFlavour.push(element.productName);
                found = true;
            }
        });
        if (found) {
            break;
        }
    }


    PizzaFlavourDropdown(pizzaFlavour, pizzaFlavourDropdown, pizza_name);
    DrinkFlavourDropdown(drinkFlavour, drinkFlavourDropdown, drink_name);

    addOnsDealDropdown(addOnsArray, addOnsDropdown);
}

function PizzaFlavourDropdown(optionsArray, dropdown, name) {
    dropdown.innerHTML = "";
    label = document.getElementById("addOnsLabel");
    label.textContent = "Select Pizza Flavour";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'Default';
    defaultOption.value = name;
    dropdown.add(defaultOption);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }
}

function DrinkFlavourDropdown(optionsArray, dropdown, name) {
    dropdown.innerHTML = "";
    label = document.getElementById("prodVariationLabel");
    label.textContent = "Select Drink Flavour";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'Default';
    defaultOption.value = name;
    dropdown.add(defaultOption);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }
}

function addOnsDealDropdown(optionsArray, dropdown) {
    dropdown.innerHTML = "";

    label = document.getElementById("drinkFlavourLabel");
    label.textContent = "Select Addon";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'None';
    defaultOption.value = '';
    dropdown.add(defaultOption);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }

    dropdown.addEventListener('change', () => {

        document.getElementById('prodQuantity').value = '1';
        let totalPriceElement = document.getElementById('totalprice');

        let selectedOption = dropdown.options[dropdown.selectedIndex];

        let priceElement = document.getElementById('price');
        let match = priceElement.textContent.match(/Rs\. (\d+)/);
        let price = match ? match[1] : 0;

        let match1 = selectedOption.value.match(/Rs\. (\d+)/);
        let price1 = match1 ? match1[1] : 0;

        const order_price = parseFloat(price) + parseFloat(price1);
        totalPriceElement.textContent = 'Rs. ' + order_price;
        priceElement.textContent = order_price;
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
    productVariationDropdown.innerHTML = "";

    let productVariations = [];
    let drinkFlavour = [];

    if (product.category_name.toLowerCase() === "pizza") {
        let addOnsArray = [];
        document.getElementById("prodVariationLabel").style.display = 'block';
        document.getElementById('addOnsLabel').style.display = 'none'
        allProducts.forEach(element => {
            if (element.category_name.toLowerCase() == 'addons') {
                if (element.productName.includes('Topping')) {
                    addOnsArray.push(`${element.productName} (Rs. ${element.productPrice})`);
                }
            }
            if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
                productVariations.push(`${element.productSize} (Rs. ${element.productPrice})`);
            }
        });
        addOnsDropdown(addOnsArray, drinkFlavourDropdown);
        addOptionsToDropdown(productVariations, productVariationDropdown);

    } else if (product.category_name.toLowerCase() === "drinks") {

        document.getElementById('addOnsLabel').style.display = 'block'
        document.getElementById("prodVariationLabel").style.display = 'block';
        let uniqueDrinkFlavours = new Set();

        allProducts.forEach(element => {
            if (element.category_name.toLowerCase() == 'drinks') {
                uniqueDrinkFlavours.add(element.productName);
            }
            if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
                productVariations.push(`${element.productSize} (Rs. ${element.productPrice})`);
            }
        });

        drinkFlavour = Array.from(uniqueDrinkFlavours);
        addOnsDropdown(drinkFlavour, drinkFlavourDropdown);
        addOptionsToDropdown(productVariations, productVariationDropdown);

        drinkFlavourDropdown.addEventListener('change', () => {
            let selectedFlavour = drinkFlavourDropdown.value;
            let filteredVariations = allProducts.filter(product => product.category_name.toLowerCase() === "drinks" && product.productName === selectedFlavour);

            if (filteredVariations.length > 0) {
                let selectedProduct = filteredVariations[0];
                let variationOptions = filteredVariations.map(product => `${product.productSize} (Rs. ${product.productPrice})`);
                document.getElementById('price').textContent = 'Rs. ' + selectedProduct.productPrice;
                document.getElementById('totalprice').textContent = 'Rs. ' + selectedProduct.productPrice;
                addOptionsToDropdown(variationOptions, productVariationDropdown);
            }
        });

    } else if (product.category_name.toLowerCase() === "burger") {
        let addOnsArray = [];
        document.getElementById("prodVariationLabel").style.display = 'block';
        document.getElementById('addOnsLabel').style.display = 'block';
        allProducts.forEach(element => {
            if (element.category_name.toLowerCase() == 'addons') {
                if (!element.productName.includes('Topping')) {
                    addOnsArray.push(`${element.productName} (Rs. ${element.productPrice})`);
                }
            }
            if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
                productVariations.push(`${element.productSize} (Rs. ${element.productPrice})`);
            }
        });
        addOnsDropdownBurger(addOnsArray, drinkFlavourDropdown)
        addOptionsToDropdownBurger(productVariations, productVariationDropdown);

    } else {
        document.getElementById('addOnsLabel').style.display = 'block'
        document.getElementById("prodVariationLabel").style.display = 'block';

        allProducts.forEach(element => {
            if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
                productVariations.push(`${element.productSize} (Rs. ${element.productPrice})`);
                drinkFlavour.push(element.productName);
            }
        });
        addOptionsToDropdown(productVariations, productVariationDropdown);
    }
}

function addOptionsToDropdown(optionsArray, dropdown) {
    dropdown.innerHTML = "";
    label = document.getElementById("prodVariationLabel");
    label.textContent = "Select Variation";

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

function addOnsDropdown(optionsArray, dropdown) {
    dropdown.innerHTML = "";

    label = document.getElementById("addOnsLabel");
    label.textContent = "Select Drink Flavour";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'Select Addon';
    defaultOption.value = '';
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

function addOptionsToDropdownBurger(optionsArray, dropdown) {
    dropdown.innerHTML = "";
    label = document.getElementById("prodVariationLabel");
    label.textContent = "Select Combo";

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

function addOnsDropdownBurger(optionsArray, dropdown) {
    dropdown.innerHTML = "";

    label = document.getElementById("addOnsLabel");
    label.textContent = "Select Addon";

    let defaultOption = document.createElement("option");
    defaultOption.text = 'None';
    defaultOption.value = '';
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

let allAddedProducts = [];
let index = 1;

function add(allProducts) {

    // for (var i = 0; i < arguments.length; i++) {
    //     console.log("Argument " + (i + 1) + ":", arguments[i]);
    // }

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
}

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
    orderPrice.value = "Total Bill:\t\t Rs. " + totalOrderPricesSum;
}

function increaseWhole(productId, quantityInputId, totalSpanId, hiddenInputId) {
    let orderPrice = document.getElementById('totalbill');
    let quantityInputElement = document.getElementById(quantityInputId);
    let totalBillString = document.getElementById(totalSpanId);
    let data = document.getElementById(hiddenInputId);
    let parsedData = JSON.parse(data.value);

    let currentOrderItemQuantity = parseInt(quantityInputElement.value);

    if (!(productId in initialTotalPrices)) {
        let pattern = /\d+\.\d+/;
        let match = totalBillString.textContent.match(pattern);
        let initialPrice = match ? parseFloat(match[0]) : 0;
        initialTotalPrices[productId] = initialPrice / currentOrderItemQuantity;
        product_details[productId] = parsedData;
    }

    let newOrderItemQuantity = currentOrderItemQuantity + 1;
    product_details[productId].quantity = newOrderItemQuantity;
    quantityInputElement.value = newOrderItemQuantity;

    let totalPrice = initialTotalPrices[productId] * newOrderItemQuantity;
    product_details[productId].price = totalPrice;
    data.value = JSON.stringify(product_details[productId]);
    TotalOrderPrices[productId] = totalPrice;

    totalBillString.textContent = "Total : Rs. " + totalPrice.toFixed(2);

    updateTotalOrderPricesSum();
}

function decreaseWhole(OrderElementId, productId, quantityInputId, totalSpanId, hiddenInputId) {
    let quantityInputElement = document.getElementById(quantityInputId);
    let totalBillString = document.getElementById(totalSpanId);
    let OrderElementData = document.getElementById(hiddenInputId);
    let parsedData = JSON.parse(OrderElementData.value);
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
        OrderElementData.value = JSON.stringify(product_details[productId]);
        totalBillString.textContent = "Total : Rs. " + totalPrice.toFixed(2);

        TotalOrderPrices[productId] = totalPrice;
        updateTotalOrderPricesSum();
    } else {
        let response = confirm('Are you sure you want to remove the product?');
        if (response) {
            let OrderElement = document.getElementById(OrderElementId);
            OrderElement.parentNode.removeChild(OrderElement);
            OrderElementData.remove();
            OrderElementData.value = '';

            delete initialTotalPrices[productId];
            delete product_details[productId];
            delete TotalOrderPrices[productId];

            updateTotalOrderPricesSum();
        } else {
            alert('Minimum quantity should be 1.');
        }
    }
}


/*
let initialTotalPrices = {};
let TotalOrderPrices = {};
let product_details = {};

function increaseWhole(productId, quantityInputId, totalSpanId, hiddenInputId) {
    let orderPrice = document.getElementById('totalbill');
    let quantityInputElement = document.getElementById(quantityInputId);
    let totalBillString = document.getElementById(totalSpanId);
    let data = document.getElementById(hiddenInputId);
    let parsedData = JSON.parse(data.value);

    let currentOrderItemQuantity = parseInt(quantityInputElement.value);

    if (!(productId in initialTotalPrices)) {
        let pattern = /\d+\.\d+/;
        let match = totalBillString.textContent.match(pattern);
        let initialPrice = match ? parseFloat(match[0]) : 0;
        initialTotalPrices[productId] = initialPrice / currentOrderItemQuantity;
        product_details[productId] = parsedData;
    }

    let newOrderItemQuantity = currentOrderItemQuantity + 1;
    product_details[productId].quantity = newOrderItemQuantity;
    quantityInputElement.value = newOrderItemQuantity;

    let totalPrice = initialTotalPrices[productId] * newOrderItemQuantity;
    product_details[productId].price = totalPrice;
    data.value = JSON.stringify(product_details[productId]);
    TotalOrderPrices[productId] = totalPrice;
    
    // Calculate the sum of TotalOrderPrices
    let totalOrderPricesSum = Object.values(TotalOrderPrices).reduce((acc, curr) => acc + curr, 0);
    console.log("Sum of TotalOrderPrices:", totalOrderPricesSum);
    orderPrice.value = "Total Bill:\t\t Rs. " +totalOrderPricesSum;
    totalBillString.textContent = "Total : Rs. " + totalPrice.toFixed(2);
}

function decreaseWhole(OrderElementId, productId, quantityInputId, totalSpanId, hiddenInputId) {
    let quantityInputElement = document.getElementById(quantityInputId);
    let totalBillString = document.getElementById(totalSpanId);
    let OrderElementData = document.getElementById(hiddenInputId);
    let parsedData = JSON.parse(OrderElementData.value);
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
        OrderElementData.value = JSON.stringify(product_details[productId]);
        totalBillString.textContent = "Total : Rs. " + totalPrice.toFixed(2);
    } else {
        let response = confirm('Are you sure you want to remove the product?');
        if (response) {
            let OrderElement = document.getElementById(OrderElementId);
            OrderElement.parentNode.removeChild(OrderElement);
            OrderElementData.remove();
            OrderElementData.value = '';
        } else {
            alert('Minimum quantity should be 1.');
        }
    }
}*/


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
function add(allProducts) {
        let productName = document.getElementById('prodName').textContent;
    let product = productName.split(" ");
    let prod = product[0];
    productName = productName.replace(prod, "");

    let productVariation = document.getElementById('prodVariation').value;
    let addOns = document.getElementById('addons').value;
    let productPrice = parseFloat(document.getElementById('totalprice').textContent.replace('Rs. ', ''));
    let quantity = document.getElementById('prodQuantity').value;

    let extractedText;

    let productObj = {
        name: productName,
        variation: productVariation.replace(/\s+/g, ''),
        addons: addOns.replace(/\s*\(Rs\.\s*\d+\)\s*-/, ""),
        price: productPrice,
        quantity: quantity.replace(/\s+/g, ' ')
    };


    allAddedProducts.push(productObj);

    let pTag = document.createElement('p');
    pTag.style.borderBottom = '1px solid #000';
    let textarea = document.createElement('textarea');
    textarea.name = "product";
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

    let decreaseIcon = document.createElement('i');
    decreaseIcon.style.fontSize = '2.5vw';
    decreaseIcon.style.color = '#d40000';
    decreaseIcon.className = 'bx bxs-checkbox-minus';
    decreaseIcon.setAttribute('onclick', 'decrease()');

    let quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = 'prodQuantity';
    quantityInput.id = 'prodQuantity';
    quantityInput.style.width = '30px';
    quantityInput.style.textAlign = 'center';
    quantityInput.value = quantity;

    let increaseIcon = document.createElement('i');
    increaseIcon.style.fontSize = '2vw';
    increaseIcon.style.color = '#d40000';
    increaseIcon.className = 'bx bxs-plus-square';
    increaseIcon.setAttribute('onclick', 'increase()');

    divQuantity.appendChild(decreaseIcon);
    divQuantity.appendChild(quantityInput);
    divQuantity.appendChild(increaseIcon);

    pTag.appendChild(textarea);
    pTag.appendChild(divQuantity);

    document.getElementById('selectedProducts').appendChild(pTag);

    if (!addOns) {
        let productDetails = quantity.replace(/\s+/g, ' ') + ' ' + productVariation.replace(/\s+/g, '') + productName;
        extractedText = productDetails.replace(/\(.*?\)/, '');
        extractedText = extractedText.trim();

    } else {
        let productDetails = quantity.replace(/\s+/g, ' ') + ' ' + productVariation.replace(/\s+/g, '') + productName + ' with extra ' + addOns.replace(/\s*\(Rs\.\s*\d+\)\s*./, "");
        allProducts.forEach(element => {
            if (element.productName == addOns) {
                if (element.category_name.toLowerCase() == 'drinks') {
                    productName = addOns;
                    productDetails = quantity.replace(/\s+/g, ' ') + ' ' + productVariation.replace(/\s+/g, '') + ' ' + addOns;
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

    let currentTotal = totalBillValue + productPrice;    
    document.getElementById('totalbill').value = "Total Bill:\t\t Rs. " + currentTotal.toFixed(2);

    closeAddToCart();

    document.getElementById('prodVariation').value = '';
    document.getElementById('addons').value = '';
    document.getElementById('prodQuantity').value = '1';
}

function add(allProducts) {
        let productName = document.getElementById('prodName').textContent;
    let product = productName.split(" ");
    let prod = product[0];
    productName = productName.replace(prod, "");

    let productVariation = document.getElementById('prodVariation').value;
    let addOns = document.getElementById('addons').value;
    let productPrice = parseFloat(document.getElementById('totalprice').textContent.replace('Rs. ', ''));
    let quantity = document.getElementById('prodQuantity').value;

    let extractedText;

    let productObj = {
        name: productName,
        variation: productVariation.replace(/\s+/g, ''),
        addons: addOns.replace(/\s*\(Rs\.\s*\d+\)\s*, ""),
        price: productPrice,
        quantity: quantity.replace(/\s+/g, ' ')
    };


    allAddedProducts.push(productObj);

    let pTag = document.createElement('p');
    pTag.style.borderBottom = '1px solid #000';
    let textarea = document.createElement('textarea');
    textarea.name = "product";
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

    let decreaseIcon = document.createElement('i');
    decreaseIcon.style.fontSize = '2.5vw';
    decreaseIcon.style.color = '#d40000';
    decreaseIcon.className = 'bx bxs-checkbox-minus';
    decreaseIcon.setAttribute('onclick', 'decrease()');

    let quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = 'prodQuantity';
    quantityInput.id = 'prodQuantity';
    quantityInput.style.width = '30px';
    quantityInput.style.textAlign = 'center';
    quantityInput.value = quantity;

    let increaseIcon = document.createElement('i');
    increaseIcon.style.fontSize = '2vw';
    increaseIcon.style.color = '#d40000';
    increaseIcon.className = 'bx bxs-plus-square';
    increaseIcon.setAttribute('onclick', 'increase()');

    divQuantity.appendChild(decreaseIcon);
    divQuantity.appendChild(quantityInput);
    divQuantity.appendChild(increaseIcon);

    pTag.appendChild(textarea);
    pTag.appendChild(divQuantity);

    document.getElementById('selectedProducts').appendChild(pTag);

    if (!addOns) {
        let productDetails = quantity.replace(/\s+/g, ' ') + ' ' + productVariation.replace(/\s+/g, '') + productName;
        extractedText = productDetails.replace(/\(.*?\)/, '');
        extractedText = extractedText.trim();

    } else {
        let productDetails = quantity.replace(/\s+/g, ' ') + ' ' + productVariation.replace(/\s+/g, '') + productName + ' with extra ' + addOns.replace(/\s*\(Rs\.\s*\d+\)\s*, "");
        allProducts.forEach(element => {
            if (element.productName == addOns) {
                if (element.category_name.toLowerCase() == 'drinks') {
                    productName = addOns;
                    productDetails = quantity.replace(/\s+/g, ' ') + ' ' + productVariation.replace(/\s+/g, '') + ' ' + addOns;
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

    let currentTotal = totalBillValue + productPrice;    
    document.getElementById('totalbill').value = "Total Bill:\t\t Rs. " + currentTotal.toFixed(2);

    closeAddToCart();

    document.getElementById('prodVariation').value = '';
    document.getElementById('addons').value = '';
    document.getElementById('prodQuantity').value = '1';
}


let initialTotalPrice = null;
function increaseWhole(quantityInputId, totalSpanId) {
    let quantityInputElement = document.getElementById(quantityInputId);
    let totalBillString = document.getElementById(totalSpanId);
    if (initialTotalPrice === null) {
        let pattern = /\d+\.\d+/;
        let match = totalBillString.textContent.match(pattern);
        initialTotalPrice = match ? parseFloat(match[0]) : 0;
    }
    let currentOrderItemQuantityStr = quantityInputElement.value;
    let currentOrderItemQuantity = parseInt(currentOrderItemQuantityStr);
    let newOrderItemQuantity = currentOrderItemQuantity + 1;
    quantityInputElement.value = newOrderItemQuantity;
    let totalPrice = initialTotalPrice * newOrderItemQuantity;
    totalBillString.textContent = "Total : Rs. " + totalPrice.toFixed(2);
    console.log(totalPrice);
}

function add() {
    let productName = document.getElementById('prodName').textContent;
    let product = productName.split(" ");
    let prod = product[0];
    productName = productName.replace(prod, "");

    let productVariation = document.getElementById('prodVariation').value;
    let addOns = document.getElementById('addons').value;

    let productPrice = document.getElementById('totalprice').textContent.replace('Rs. ', '');
    let quantity = document.getElementById('prodQuantity').value;

    let extractedText;

    let totalPrice = parseFloat(productPrice) * parseInt(quantity);
    let totalBillInput = document.getElementById('totalbill');
    let currentTotal = parseFloat(totalBillInput.value);
    let newTotal = currentTotal + totalPrice;
    totalBillInput.value = newTotal.toFixed(2);
    alert(totalBillInput.value);
    let textarea = document.getElementById('selectedProducts');

    if (!addOns) {
        let productDetails = quantity + ' ' + productVariation + ' ' + productName;
        extractedText = productDetails.replace(/\(.*?\)/, '');
        extractedText = extractedText.trim();

    } else {
        let productDetails = quantity + ' ' + productVariation + ' ' + addOns;
        extractedText = productDetails.replace(/\(.*?\)/, '');
        extractedText = extractedText.trim();

    }

    let Name = productName;
    textarea.value += Name + '\n' + extractedText + '\t' + productPrice + '\n';

    document.getElementById('prodQuantity').value = '1';

    closeAddToCart();
}
 */

/*
window.addEventListener('beforeunload', function (event) {
    let textareaValue = document.getElementById('selectedProducts').value;
    let totalBillValue = document.getElementById('totalbill').value;
    localStorage.setItem('textareaValue', textareaValue);
    localStorage.setItem('totalBillValue', totalBillValue);

window.addEventListener('DOMContentLoaded', function (event) {
        let savedTextareaValue = localStorage.getItem('textareaValue');
    let savedTotalBillValue = localStorage.getItem('totalBillValue');
    if (savedTextareaValue) {
            document.getElementById('selectedProducts').value = savedTextareaValue;
        }
        if (savedTotalBillValue) {
        document.getElementById('totalbill').value = savedTotalBillValue;
    }

    window.addEventListener('keydown', function (event) {
        if ((event.ctrlKey && event.shiftKey && event.keyCode === 82) || (event.ctrlKey && event.keyCode === 82)) {
            document.getElementById('selectedProducts').value = '';
            document.getElementById('totalbill').value = 0;
            window.location.reload(true);
        }
    });
});*/

/*
function addOptionsToDropdownBurger(optionsArray, dropdown) {

    let defaultOption = document.createElement("option");
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.text = "Combo";
    dropdown.add(defaultOption);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }
}

function addOnsDropdownBurger(optionsArray, dropdown) {

    let defaultvariation = document.createElement("option");
    defaultvariation.disabled = true;
    defaultvariation.selected = true;
    defaultvariation.text = "Add On";
    defaultvariation.value = '';
    dropdown.add(defaultvariation);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }
}

function updateProductSizeDropdown(Product) {

    let productVariationDropdown = document.getElementById("prodVariation");
    productVariationDropdown.innerHTML = "";

    let addons = document.getElementById('addons');
    addons.innerHTML = "";

    if (category.toLowerCase() === "drinks") {
        addons.style.display = 'block';
        let drinkProductSizes = ["Regular", "1 Liter Drink", "1.5 Liter Drink"];
        addOptionsToDropdown(drinkProductSizes, productVariationDropdown);
        let drinkProductvariations = ["Pepsi", "Coco Cola", "Fanta", "7up", "Dew", "Sprite"];
        addOnsDropdown(drinkProductvariations, addons);

    } else if (category.toLowerCase() === "appetizer") {
        addons.style.display = 'block';
        let foodProductSizes = ["3 Pieces", "6 Pieces", "12 Pieces"];
        addOptionsToDropdown(foodProductSizes, productVariationDropdown);

    } else if (category.toLowerCase() === "fries") {
        addons.style.display = 'none';
        let foodProductSizes = ["Regular", "Large"];
        addOptionsToDropdown(foodProductSizes, productVariationDropdown);

    } else if (category.toLowerCase() === "pizza") {
        addons.style.display = 'none';
        let foodProductSizes = ["Small", "Regular", "Large", "Party"];
        addOptionsToDropdown(foodProductSizes, productVariationDropdown);

    } else if (category.toLowerCase() === "burger") {
        addons.style.display = 'block';
        let foodProductSizes = ["Burger", "Fries + Reg Drink"];
        // addOptionsToDropdownBurger(foodProductSizes, productVariationDropdown);
        let drinkProductvariations = ["Chease Slice"];
        // addOnsDropdownBurger(drinkProductvariations, addons);

    } else {
        addons.style.display = 'block';
        let foodProductSizes = ["Small", "Medium", "Large", "Extra Large", "Jumbo"];
        addOptionsToDropdown(foodProductSizes, productVariationDropdown);
    } else if (product.category_name.toLowerCase() === "burger") {
        document.getElementById('addOnsLabel').style.display = 'block'
        document.getElementById("prodVariationLabel").style.display ='block';

        allProducts.forEach(element => {
            if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
                productVariations.push(`${element.productSize} (Rs. ${element.productPrice})`);
                drinkFlavour.push(element.productName);
            }
        });
    addOptionsToDropdown(productVariations, productVariationDropdown);
}*/
