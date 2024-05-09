@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/deal.css') }}">
@endpush

@section('main')
    <main id="dealProducts">
        <div class="path">
            <h2>Add products to Deal</h2>
        </div>

        <section class="products">
            @foreach ($Products as $product)
                <div class="imgbox" onclick="toggleProductSelection(this)">
                    <img src="{{ asset('Images/ProductImages/' . $product->productImage) }}" alt="Product">
                    <p class="category_name">{{ $product->category_name }}</p>
                    <p class="product_name">{{ $product->productName }}</p>
                    <p class="product_size">{{ $product->productSize }}</p>
                    <p class="product_price">{{ $product->productPrice }} Pkr</p>
                </div>
            @endforeach
        </section>
        @php
            $id = session('id');
        @endphp
        <div id="data">
            <input type="hidden" id="products" required>
            <input type="hidden" id="size">
            <input type="hidden" id="price">
            <input type="button" id="adddeal" value="Add Product to Deal" onclick="dealDetails()">
        </div>

        <div id="dealProductInfoOverlay"></div>
        <form class="dealProdInfo" id="dealProdInfo" action="{{ route('createDealProducts') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Deal Details</h3>
            <hr>

            <input type="hidden" name="id" value="{{ $id }}">

            <div class="inputdivs" id="productsNames">
            </div>

            <div class="inputdiv">
                <label for="dealPrice">Deal Price:</label>
                <input type="text" name="currentDealPrice" id="currentDealPrice" required>

                <label for="dealFinalPrice">Deal Final Price:</label>
                <input type="number" name="dealFinalPrice" id="dealFinalPrice" placeholder="Enter Final Price of Deal"
                    required>
            </div>

            <div class="btns">
                <button type="button" id="cancel" onclick="closeDetails()">Cancel</button>
                <input type="button" value="Calculate" onclick="calculatedealPrice()">
                <input type="submit" value="Confirm Deal">
            </div>
        </form>

    </main>
    <script>
        function toggleProductSelection(element) {
            let productName = element.querySelector('.product_name').textContent;
            let productSize = element.querySelector('.product_size').textContent;
            let productPrice = element.querySelector('.product_price').textContent;

            let productNameField = document.getElementById('products');
            let productSizeField = document.getElementById('size');
            let productPriceField = document.getElementById('price');

            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                productNameField.value = productNameField.value.replace(productName + ',', '');
                productSizeField.value = productSizeField.value.replace(productSize + ',', '');
                productPriceField.value = productPriceField.value.replace(productPrice + ',', '');
            } else {
                element.classList.add('selected');
                productNameField.value += productName + ',';
                productSizeField.value += productSize + ',';
                productPriceField.value += productPrice + ',';
            }
        }

        let dealPrice = 0;

        function dealDetails() {
            let products = document.getElementById('products').value;

            if (products.trim() === '') {
                alert("Select a Product First");
                closeDetails();
            } else {
                let overlay = document.getElementById('dealProductInfoOverlay');
                let prompt = document.getElementById('dealProdInfo');
                let container = document.getElementById('productsNames');

                let productNameArray = products.split(',').filter(Boolean);
                let sizes = document.getElementById('size').value;
                let sizeArray = sizes.split(',').filter(Boolean);
                let prices = document.getElementById('price').value;
                let priceArray = prices.split(',').filter(Boolean);
                container.innerHTML = '';
                
                let productsDetails = [];
                
                for (let i = 0; i < productNameArray.length; i++) {
                    let product = {
                        name: productNameArray[i],
                        size: sizeArray[i],
                        price: priceArray[i]
                    };
                    productsDetails.push(product);
                }
                alert(sizeArray);

                productsDetails.forEach((product, index) => {
                    const prod_div = document.createElement("div");
                    const label_name = document.createElement("label");
                    const size_input = document.createElement("input");
                    const name_input = document.createElement("input");
                    const label_quantity = document.createElement("label");
                    const quantity_input = document.createElement("input");
                    const label_total_price = document.createElement("label");
                    const total_price_input = document.createElement("input");

                    prod_div.style.display = 'flex';
                    prod_div.style.margin = 'auto';
                    prod_div.style.alignItems = 'center';
                    prod_div.style.flexWrap = 'wrap';

                    label_name.textContent = "Product Name:";
                    label_name.style.marginRight = '5px';

                    size_input.type = "text";
                    size_input.style.margin = '5px';
                    size_input.value = product.size;
                    size_input.readOnly = true;
                    size_input.name = 'product_variation_' + index;

                    name_input.type = "text";
                    name_input.style.margin = '5px';
                    name_input.value = product.name;
                    name_input.readOnly = true;
                    name_input.name = 'product_name_' + index;

                    label_quantity.textContent = "Quantity:";
                    label_quantity.style.marginRight = '5px';

                    quantity_input.type = "number";
                    quantity_input.min = "1";
                    quantity_input.style.margin = '5px';
                    quantity_input.placeholder = 'Enter Product Quantity';
                    quantity_input.addEventListener('input', function() {
                        calculateTotal(this, total_price_input, product.price);
                    });
                    quantity_input.id = 'quantity_input_' + index;
                    quantity_input.name = 'product_quantity_' + index;

                    label_total_price.textContent = "Total Price:";
                    label_total_price.style.marginRight = '5px';

                    total_price_input.type = "text";
                    total_price_input.classList.add('total-price');
                    total_price_input.style.margin = '5px';
                    total_price_input.readOnly = true;
                    total_price_input.name = 'product_total_price_' + index;

                    prod_div.appendChild(label_name);
                    prod_div.appendChild(size_input);
                    prod_div.appendChild(name_input);
                    prod_div.appendChild(label_quantity);
                    prod_div.appendChild(quantity_input);
                    prod_div.appendChild(label_total_price);
                    prod_div.appendChild(total_price_input);
                    container.appendChild(prod_div);
                });

                overlay.style.display = 'block';
                prompt.style.display = 'flex';
            }
        }

        function calculateTotal(quantity_input, total_price_input, price) {
            let quantity = parseInt(quantity_input.value);
            let total = isNaN(quantity) ? 0 : quantity * parseFloat(price);
            total_price_input.value = total.toFixed(2) + ' Pkr';

            return total;
        }

        function calculatedealPrice() {
            let dealPrice = 0;

            let totalInputs = document.querySelectorAll('.total-price');
            let parts, numericPart, currencyPart;

            totalInputs.forEach(input => {
                parts = input.value.split(' ');
                numericPart = parseFloat(parts[0]);
                currencyPart = parts[1];

                dealPrice += numericPart;
            });

            let currentDealPrice = document.getElementById('currentDealPrice');
            currentDealPrice.value = dealPrice + " " + currencyPart;
        }

        function closeDetails() {
            let overlay = document.getElementById('dealProductInfoOverlay');
            let prompt = document.getElementById('dealProdInfo');
            let totalInputs = document.querySelectorAll('.total-price');
            let currentDealPrice = document.getElementById('currentDealPrice');
            let dealFinalPrice = document.getElementById('dealFinalPrice');

            totalInputs.forEach(input => {
                input.value = "";
            });

            currentDealPrice.value = "";
            dealFinalPrice.value = "";

            overlay.style.display = 'none';
            prompt.style.display = 'none';
        }
    </script>
@endsection
