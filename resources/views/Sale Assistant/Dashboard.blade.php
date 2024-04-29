@extends('Components.Salesman')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Salesman/dashboard.css') }}">
@endpush

@section('main')
    <main id="salesman">
        <div id="productsSide">
            <div id="category_bar">
                <a href="{{ route('salesman_dashboard') }}" name="categoryName"> All</a>
                @foreach ($Categories as $category)
                    <a href="{{ route('salesman_dash', $category->categoryName) }}"
                        name="categoryName">{{ $category->categoryName }} </a>
                @endforeach
            </div>

            <div id="products">
                @foreach ($Products as $product)
                    <div class="imgbox" onclick="showAddToCart({{ json_encode($product) }})">
                        <img src="{{ asset('Images/ProductImages/' . $product->productImage) }}" alt="Product">
                        <p class="product_name">{{ $product->productName }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div id="receipt">
            <h3 id="heading">Receipt</h3>
            <form id="cart" action="{{ route('placeOrder') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea id="selectedProducts" name="products" readonly></textarea>
                <input type="text" name="totalbill" id="totalbill" value="0" readonly>
                <div id="buttons">
                    <input type="submit" value="Proceed">
                </div>
            </form>
        </div>

        <div id="overlay"></div>
        <div id="addToCart" action="" method="POST" enctype="multipart/form-data">
            <p class="head1">Customize Item</p>
            <p id="prodName"></p>
            <p id="prodPrice">Product Price <span id="price"></span></p>
            <p class="head1">Please Select</p>
            <select name="prodSize" id="prodSize">
            </select>

            <div id="quantity">
                <i onclick="decrease()" class='bx bxs-checkbox-minus'></i>
                <input type="number" name="prodQuantity" id="prodQuantity" value="1" min="1">
                <i onclick="increase()" class='bx bxs-plus-square'></i>
            </div>

            <p id="bottom">Total Price <span id="totalprice"></span></p>

            <div id="buttons">
                <button type="button" onclick="closeAddToCart()">close</button>
                <button type="button" onclick="add()">Add</button>
            </div>
        </div>

    </main>

    <script>
        function showAddToCart(product) {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('addToCart');

            document.getElementById('prodName').textContent = product.productName;
            document.getElementById('price').textContent = 'Rs. ' + product.productPrice;
            document.getElementById('totalprice').textContent = 'Rs. ' + product.productPrice;

            updateProductSizeDropdown(product.category_name);

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeAddToCart() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('addToCart');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

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

                totalPrice = totalPrice / (currentValue + 1); // Divide by previous quantity
                totalPriceElement.textContent = "Rs. " + totalPrice.toFixed(2);
            }
        }


        function updateProductSizeDropdown(category) {

            let productSizeDropdown = document.getElementById("prodSize");
            productSizeDropdown.innerHTML = "";

            if (category == "Drinks") {
                let drinkProductSizes = ["250ml", "1ltr", "1.5ltr"];
                addOptionsToDropdown(drinkProductSizes, productSizeDropdown);
            } else {
                let foodProductSizes = ["Small", "Medium", "Large", "Extra Large", "Jumbo"];
                addOptionsToDropdown(foodProductSizes, productSizeDropdown);
            }
        }

        function addOptionsToDropdown(optionsArray, dropdown) {
            document.getElementByQ
            let defaultOption = document.createElement("option");
            defaultOption.disabled = true;
            defaultOption.selected = true;
            defaultOption.text = "Select Product Size";
            dropdown.add(defaultOption);

            for (let i = 0; i < optionsArray.length; i++) {
                let option = document.createElement("option");
                option.text = optionsArray[i];
                option.value = optionsArray[i];
                dropdown.add(option);
            }
        }

        function add() {
            let productName = document.getElementById('prodName').textContent;
            let productPrice = document.getElementById('price').textContent.replace('Rs. ', '');
            let productSize = document.getElementById('prodSize').value;
            let quantity = document.getElementById('prodQuantity').value;

            let totalPrice = parseFloat(productPrice) * parseInt(quantity);
            let productDetails = quantity + ' ' + productSize + ' ' + productName + ' ' + (productPrice * quantity);
            let textarea = document.getElementById('selectedProducts');
            textarea.value += productDetails + ' Pkr\n';

            let totalBillInput = document.getElementById('totalbill');
            let currentTotal = parseFloat(totalBillInput.value);
            let newTotal = currentTotal + totalPrice;
            totalBillInput.value = newTotal.toFixed(2);
            closeAddToCart();
        }

        window.addEventListener('beforeunload', function(event) {
            let textareaValue = document.getElementById('selectedProducts').value;
            let totalBillValue = document.getElementById('totalbill').value;
            localStorage.setItem('textareaValue', textareaValue);
            localStorage.setItem('totalBillValue', totalBillValue);
        });


        window.addEventListener('DOMContentLoaded', function(event) {
            let savedTextareaValue = localStorage.getItem('textareaValue');
            let savedTotalBillValue = localStorage.getItem('totalBillValue');
            if (savedTextareaValue) {
                document.getElementById('selectedProducts').value = savedTextareaValue;
            }
            if (savedTotalBillValue) {
                document.getElementById('totalbill').value = savedTotalBillValue;
            }

            window.addEventListener('keydown', function(event) {
                if ((event.ctrlKey && event.shiftKey && event.keyCode === 82)||(event.ctrlKey && event.keyCode === 82   )) {
                    document.getElementById('selectedProducts').value = '';
                    document.getElementById('totalbill').value = 0;
                    window.location.reload(true);
                }
            });
        });
    </script>
@endsection
