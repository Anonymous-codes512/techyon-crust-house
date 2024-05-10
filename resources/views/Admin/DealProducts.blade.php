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
                    <p class="product_id">{{ $product->id }}</p>
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
            <input type="hidden" id="productIds" required>
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
        
        let dealPrice = 0;
        function toggleProductSelection(element) {
            let productId = element.querySelector('.product_id').textContent;
            let productName = element.querySelector('.product_name').textContent;
            let productSize = element.querySelector('.product_size').textContent;
            let productPrice = element.querySelector('.product_price').textContent;

            let productIdField = document.getElementById('productIds');
            let productNameField = document.getElementById('products');
            let productSizeField = document.getElementById('size');
            let productPriceField = document.getElementById('price');

            if (element.classList.contains('selected')) {
                element.classList.remove('selected');

                productIdField.value = productIdField.value.replace(productId + ',', '');
                productNameField.value = productNameField.value.replace(productName + ',', '');
                productSizeField.value = productSizeField.value.replace(productSize + ',', '');
                productPriceField.value = productPriceField.value.replace(productPrice + ',', '');

            } else {
                element.classList.add('selected');

                productIdField.value += productId + ',';
                productNameField.value += productName + ',';
                productSizeField.value += productSize + ',';
                productPriceField.value += productPrice + ',';
            }
        }

        function dealDetails() {
            let products = document.getElementById('products').value;
            if (products.trim() === '') {
                alert("Select a Product First");
                closeDetails();
            } else {
                let overlay = document.getElementById('dealProductInfoOverlay');
                let prompt = document.getElementById('dealProdInfo');

                let Ids = document.getElementById('productIds').value;
                let productIdArray = Ids.split(',').filter(Boolean);

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
                        id: productIdArray[i],
                        name: productNameArray[i],
                        size: sizeArray[i],
                        price: priceArray[i]
                    };
                    productsDetails.push(product);
                }

                const div = document.createElement('div');
                div.style.display = 'flex';
                div.style.margin = 'auto';
                div.style.alignItems = 'center';
                div.style.flexWrap = 'wrap';

                const RowData1 = document.createElement('p');
                RowData1.display = 'flex';
                RowData1.style.margin = '5px';
                RowData1.style.width = '184px';
                RowData1.textContent = "Product Name";
                
                const RowData2 = document.createElement('p');
                RowData2.display = 'flex';
                RowData2.style.margin = '5px';
                RowData2.style.width = '184px';
                RowData2.textContent = "Product Variation";

                const RowData3 = document.createElement('p');
                RowData3.display = 'flex';
                RowData3.style.margin = '5px';
                RowData3.textContent = "Quantity";
                RowData3.style.width = '184px';

                const RowData4 = document.createElement('p');
                RowData4.display = 'flex';
                RowData4.style.margin = '5px';
                RowData4.textContent = "Total Price";
                RowData4.style.width = '184px';

                div.appendChild(RowData1);
                div.appendChild(RowData2);
                div.appendChild(RowData3);
                div.appendChild(RowData4);
                container.appendChild(div);

                productsDetails.forEach((product, index) => {

                    const prod_div = document.createElement("div");
                    const id_input = document.createElement("input");
                    const size_input = document.createElement("input");
                    const name_input = document.createElement("input");
                    const quantity_input = document.createElement("input");
                    const total_price_input = document.createElement("input");

                    prod_div.style.display = 'flex';
                    prod_div.style.margin = 'auto';
                    prod_div.style.alignItems = 'center';
                    prod_div.style.flexWrap = 'wrap';

                    id_input.type = "hidden";
                    id_input.value = product.id;
                    id_input.name = 'product_id' + index;

                    name_input.type = "text";
                    name_input.style.margin = '5px';
                    name_input.value = product.name;
                    name_input.readOnly = true;
                    name_input.name = 'product_name_' + index;

                    size_input.type = "text";
                    size_input.style.margin = '5px';
                    size_input.value = product.size;
                    size_input.readOnly = true;
                    size_input.name = 'product_variation_' + index;

                    quantity_input.min = "1";
                    quantity_input.style.margin = '5px';
                    quantity_input.placeholder = 'Enter Product Quantity';
                    quantity_input.addEventListener('input', function() {
                        calculateTotal(this, total_price_input, product.price);
                    });
                    quantity_input.id = 'quantity_input_' + index;
                    quantity_input.name = 'product_quantity_' + index;

                    total_price_input.type = "text";
                    total_price_input.classList.add('total-price');
                    total_price_input.style.margin = '5px';
                    total_price_input.readOnly = true;
                    total_price_input.name = 'product_total_price_' + index;

                    prod_div.appendChild(id_input);
                    prod_div.appendChild(name_input);
                    prod_div.appendChild(size_input);
                    prod_div.appendChild(quantity_input);
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
