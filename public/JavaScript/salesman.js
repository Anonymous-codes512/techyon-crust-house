function showAddToCart(product, allProducts) {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('addToCart');

    if (product.category_name) {

        let name = document.getElementById('prodName');
        name.textContent = product.productSize + " " + product.productName;
        document.getElementById('price').textContent = 'Rs. ' + product.productPrice;
        document.getElementById('totalprice').textContent = 'Rs. ' + product.productPrice;

        if (product.category_name.toLowerCase() != 'others') {

            // if(product.category_name.toLowerCase() == 'burger') {
            //     let name = document.getElementById("prodName");
            //     name.textContent = product.productName;
            //     document.getElementById('addons').style.display = 'none';
            //     document.getElementById('addOnsLabel').style.display = 'none'
            //     document.getElementById("prodVariation").style.display = 'none';
            //     document.getElementById('prodVariationLabel').style.display = 'none'

            // }else{

            document.getElementById("prodVariation").style.display = 'block';
            document.getElementById('addons').style.display = 'block';
            updateProductSizeDropdown(product, allProducts);
            // }

        } else {

            let name = document.getElementById("prodName");
            name.textContent = product.productName;
            document.getElementById('addons').style.display = 'none';
            document.getElementById('addOnsLabel').style.display = 'none'
            document.getElementById("prodVariation").style.display = 'none';
            document.getElementById('prodVariationLabel').style.display = 'none'
        }

    } else {

        document.getElementById('prodName').textContent = product.dealTitle;
        document.getElementById('price').textContent = 'Rs. ' + product.dealDiscountedPrice;
        document.getElementById('totalprice').textContent = 'Rs. ' + product.dealDiscountedPrice;
        // document.getElementById("prodVariation").style.display = 'none';
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

function updateProductSizeDropdown(product, allProducts) {
    console.log(product);
    console.log(allProducts);
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
        addOnsDropdown(addOnsArray, drinkFlavourDropdown)
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

function add() {
    let productName = document.getElementById('prodName').textContent;
    product = productName.split(" ");
    prod = product[0];
    productName = productName.replace(prod, "");

    let productVariation = document.getElementById('prodVariation').value;
    let addOns = document.getElementById('addons').value;

    let productPrice = document.getElementById('totalprice').textContent.replace('Rs. ', '');
    let quantity = document.getElementById('prodQuantity').value;

    let extractedText;

    let totalPrice = parseFloat(productPrice) * parseInt(quantity);
    let totalBillInput = document.getElementById('totalbill').value;
    let currentTotal = parseFloat(totalBillInput);
    let newTotal = currentTotal + totalPrice;
    totalBillInput.value = newTotal.toFixed(2);
    alert(totalBillInput);
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

window.addEventListener('beforeunload', function (event) {
    let textareaValue = document.getElementById('selectedProducts').value;
    let totalBillValue = document.getElementById('totalbill').value;
    localStorage.setItem('textareaValue', textareaValue);
    localStorage.setItem('totalBillValue', totalBillValue);
});

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
});

// function addOptionsToDropdownBurger(optionsArray, dropdown) {

//     let defaultOption = document.createElement("option");
//     defaultOption.disabled = true;
//     defaultOption.selected = true;
//     defaultOption.text = "Combo";
//     dropdown.add(defaultOption);

//     for (let i = 0; i < optionsArray.length; i++) {
//         let option = document.createElement("option");
//         option.text = optionsArray[i];
//         option.value = optionsArray[i];
//         dropdown.add(option);
//     }
// }

// function addOnsDropdownBurger(optionsArray, dropdown) {

//     let defaultvariation = document.createElement("option");
//     defaultvariation.disabled = true;
//     defaultvariation.selected = true;
//     defaultvariation.text = "Add On";
//     defaultvariation.value = '';
//     dropdown.add(defaultvariation);

//     for (let i = 0; i < optionsArray.length; i++) {
//         let option = document.createElement("option");
//         option.text = optionsArray[i];
//         option.value = optionsArray[i];
//         dropdown.add(option);
//     }
// }

/*
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
    }

     else if (product.category_name.toLowerCase() === "burger") {
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
