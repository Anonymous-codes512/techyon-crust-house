function showAddToCart(product, allProducts) {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('addToCart');

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
            document.getElementById("prodVariation").style.display = 'none';
            document.getElementById('addons').style.display = 'none';

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
    let productVariationDropdown = document.getElementById("prodVariation");
    let drinkFlavourDropdown = document.getElementById("addons");
    productVariationDropdown.innerHTML = "";

    let productVariations = [];
    let drinkFlavour = [];

    if (product.category_name.toLowerCase() === "pizza") {

        addons.style.display = 'none';
        allProducts.forEach(element => {
            if (element.productName.toLowerCase() === product.productName.toLowerCase()) {
                productVariations.push(`${element.productSize} (Rs. ${element.productPrice})`);
            }
        });
        addOptionsToDropdown(productVariations, productVariationDropdown);

    } else if (product.category_name.toLowerCase() === "drinks") {

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
            let variationOptions = filteredVariations.map(product => `${product.productSize} (Rs. ${product.productPrice})`);

            addOptionsToDropdown(variationOptions, productVariationDropdown);
        });

    } else {

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

    let defaultOption = document.createElement("option");
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.text = "Select Variation";
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
        let price = match ? match[1] : null;
        totalPriceElement.textContent = 'Rs. ' + price;
    });
}


function addOnsDropdown(optionsArray, dropdown) {
    dropdown.innerHTML = "";

    let defaultvariation = document.createElement("option");
    defaultvariation.disabled = true;
    defaultvariation.selected = true;
    defaultvariation.text = "Drink Flavour";
    dropdown.add(defaultvariation);

    for (let i = 0; i < optionsArray.length; i++) {
        let option = document.createElement("option");
        option.text = optionsArray[i];
        option.value = optionsArray[i];
        dropdown.add(option);
    }
}



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

// function add() {
//     let productName = document.getElementById('prodName').textContent;
//     let productPrice = document.getElementById('price').textContent.replace('Rs. ', '');
//     let productSize = document.getElementById('prodVariation').value;
//     let quantity = document.getElementById('prodQuantity').value;

//     let totalPrice = parseFloat(productPrice) * parseInt(quantity);
//     let productDetails = quantity + ' ' + productSize + ' ' + productName;
//     let textarea = document.getElementById('selectedProducts');
//     textarea.value += productDetails + '\n';

//     let totalBillInput = document.getElementById('totalbill');
//     let currentTotal = parseFloat(totalBillInput.value);
//     let newTotal = currentTotal + totalPrice;
//     totalBillInput.value = newTotal.toFixed(2);
//     document.getElementById('prodQuantity').value = '1';
//     closeAddToCart();
// }

// window.addEventListener('beforeunload', function (event) {
//     let textareaValue = document.getElementById('selectedProducts').value;
//     let totalBillValue = document.getElementById('totalbill').value;
//     localStorage.setItem('textareaValue', textareaValue);
//     localStorage.setItem('totalBillValue', totalBillValue);
// });


// window.addEventListener('DOMContentLoaded', function (event) {
//     let savedTextareaValue = localStorage.getItem('textareaValue');
//     let savedTotalBillValue = localStorage.getItem('totalBillValue');
//     if (savedTextareaValue) {
//         document.getElementById('selectedProducts').value = savedTextareaValue;
//     }
//     if (savedTotalBillValue) {
//         document.getElementById('totalbill').value = savedTotalBillValue;
//     }

//     window.addEventListener('keydown', function (event) {
//         if ((event.ctrlKey && event.shiftKey && event.keyCode === 82) || (event.ctrlKey && event.keyCode === 82)) {
//             document.getElementById('selectedProducts').value = '';
//             document.getElementById('totalbill').value = 0;
//             window.location.reload(true);
//         }
//     });
// });

function increase() {
    let quantityInput = document.getElementById('prodQuantity');
    let currentValue = parseInt(quantityInput.value);
    currentValue = currentValue + 1;
    quantityInput.value = currentValue;

    let totalPriceElement = document.getElementById('totalprice');
    let numericValue = totalPriceElement.textContent.match(/\d+(\.\d+)?/);
    let totalPrice = parseFloat(numericValue);

    totalPrice = totalPrice * currentValue;
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

        let totalPriceElement = document.getElementById('totalprice');
        let numericValue = totalPriceElement.textContent.match(/\d+(\.\d+)?/);
        let totalPrice = parseFloat(numericValue);

        totalPrice = totalPrice / (currentValue + 1);
        totalPriceElement.textContent = "Rs. " + totalPrice.toFixed(2);
    }
}






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
}*/
