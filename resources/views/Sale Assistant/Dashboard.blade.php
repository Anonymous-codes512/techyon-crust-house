@extends('Components.Salesman')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Salesman/dashboard.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('JavaScript/Salesman1.js') }}"></script>
@endpush

@section('main')
    <input id="orderNo" type="hidden" value="1">
    <main id="salesman">
        @php
            $allProducts = $AllProducts;
            $id = $id;
            $cartProducts = $cartProducts;
            $totalbill = 0;
        @endphp

        <div id="productsSide">
            <div id="category_bar">
                <a href="{{ route('salesman_dashboard', $id) }}"> All</a>
                @foreach ($Categories as $category)
                    <a href="{{ route('salesman_dash', [$category->categoryName, $id]) }}">{{ $category->categoryName }} </a>
                @endforeach
                <a href="{{ route('salesman_dash', ['Deals', $id]) }}"> Deals</a>
            </div>

            <div id="products">
                @php
                    $displayedProductNames = [];
                    $displayedDealTitles = [];
                @endphp

                @if ($Products !== null)
                    @foreach ($Products as $product)
                        @if (!in_array($product->productName, $displayedProductNames))
                            @php
                                $displayedProductNames[] = $product->productName;
                            @endphp

                            <div class="imgbox"
                                onclick="showAddToCart({{ json_encode($product) }} ,null, {{ json_encode($allProducts) }})">
                                <img src="{{ asset('Images/ProductImages/' . $product->productImage) }}" alt="Product">
                                <p class="product_name">{{ $product->productName }}</p>
                                <p class="product_price">From Rs. {{ $product->productPrice }}</p>
                            </div>
                        @endif
                    @endforeach
                @elseif ($Deals !== null)
                    @foreach ($Deals as $deal)
                        @if (!in_array($deal->deal->dealTitle, $displayedDealTitles))
                            @php
                                $displayedDealTitles[] = $deal->deal->dealTitle;
                            @endphp

                            <div class="imgbox"
                                onclick="showAddToCart({{ json_encode($deal) }}, {{ json_encode($Deals) }}, {{ json_encode($allProducts) }})">
                                <img src="{{ asset('Images/DealImages/' . $deal->deal->dealImage) }}" alt="Product">
                                <p class="product_name">{{ $deal->deal->dealTitle }}</p>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div id="deals_seperate_section">
                <h3 id="deals_seperate_section_heading">Deals</h3>
                <div style="display: flex;">
                    @if ($Deals !== null)
                        @foreach ($Deals as $deal)
                            @if ($deal->deal !== null && !in_array($deal->deal->dealTitle, $displayedDealTitles))
                                @php
                                    $displayedDealTitles[] = $deal->deal->dealTitle;
                                @endphp

                                <div class="imgbox"
                                    onclick="showAddToCart({{ json_encode($deal) }}, {{ json_encode($Deals) }}, {{ json_encode($allProducts) }})">
                                    <img src="{{ asset('Images/DealImages/' . $deal->deal->dealImage) }}" alt="Product">
                                    <p class="product_name">{{ $deal->deal->dealTitle }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="product_name">No Deal Found</p>
                    @endif
                </div>
            </div>
        </div>

        <div id="receipt">
            <h3 id="heading">Receipt</h3>
            <div id="cart">

                <input type="hidden" name="salesman_id" id="salesman_id" value={{ $id }}>
                <div id="selectedProducts" name="products">
                    @foreach ($cartProducts as $Value)
                        @php
                            $priceString = $Value->totalPrice;
                            preg_match('/\d+(\.\d+)?/', $priceString, $matches);
                            $numericPart = $matches[0];
                            $totalbill = $totalbill + $numericPart;

                        @endphp
                        <div id="productdiv">
                            @if ($Value->productAddon && strpos($Value->productName, $Value->productAddon) === false)
                                <p id="product-name">{{ $Value->productName . ' with ' . $Value->productAddon }}</p>
                                <p id="product_price{{ $Value->id }}">{{ $Value->totalPrice }}</p>
                            @else
                                <p id="product-name">{{ $Value->productName }}</p>
                                <p id="product_price{{ $Value->id }}">{{ $Value->totalPrice }}</p>
                            @endif
                            <button
                                onclick="window.location='{{ route('removeOneProduct', [$Value->id, $Value->salesman_id]) }}'"
                                id="remove-product">Remove</button>

                            <div style="display:flex; text-align:center;">
                                <div style="display:flex; margin-right:70px;">Quantity </div>
                                <div style="display:flex; align-items:center;">
                                    <a style="display: flex; text-decoration:none;"
                                        href="{{ route('decreaseQuantity', [$Value->id, $Value->salesman_id]) }}">
                                        <i class='bx bxs-checkbox-minus'></i>
                                    </a>
                                    <input type="text" name="prodQuantity{{ $Value->id }}"
                                        id="product_quantity{{ $Value->id }}" value="{{ $Value->productQuantity }}"
                                        readonly style="width:30px; text-align:center;">
                                    <a style="display: flex; text-decoration:none;"
                                        href="{{ route('increaseQuantity', [$Value->id, $Value->salesman_id]) }}">
                                        <i class='bx bxs-plus-square'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('placeOrder', $id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="payment-div">
                        <div class="paymentfields">
                            <label for="totalbill">Total Bill</label>
                            <input type="text" name="totalbill" id="totalbill" value="Rs {{ $totalbill }}" readonly>
                        </div>
 
                        <div class="paymentfields">
                            <label for="recievecash">Recieve Bill</label>
                            <input type="number" name="recievecash" id="recievecash" placeholder="Recieved"
                                oninput="calculateChange()" required>
                        </div>

                        <div class="paymentfields">
                            <label for="change">Change</label>
                            <input type="number" name="change" id="change" placeholder="Change" readonly>
                        </div>

                        <div class="paymentfields">
                            <div>
                                <label for="dinein">Order type</label>
                            </div>
                            <div
                                style=" display:flex; flex-direction:row; background-color:#e2e2e2;border-radius: 10px; padding:0.5vw 2px;">
                                <div>
                                    <label for="dinein">Dine-In</label>
                                    <input type="radio" name="orderType" id="dinein" value="dine-in" checked>
                                </div>
                                <div>
                                    <label for="takeaway">Takeaway</label>
                                    <input type="radio" name="orderType" id="takeaway" value="takeaway">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="buttons">
                        <input type="submit" id="proceed" value="Proceed">
                        <button onclick="window.location='{{ route('clearCart', $id) }}'" type="button"
                            id="clearCart">Clear
                            Cart</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="overlay"></div>
        <form id="addToCart" action="{{ route('saveToCart') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="product_id" name="product_id">
            <input type="hidden" name="salesman_id" id="salesman_id" value={{ $id }}>
            <p class="head1">Customize Item</p>
            <input id="prodName" name="productname" style="border: none;" readonly>
            <p id="prodPrice">Product Price <input name="productprice" style="border: none; text-align:right;"
                    id="price" readonly></p>
            <p class="head1">Please Select</p>

            <label id="prodVariationLabel" for="prodVariation">Product Variation</label>
            <select name="prodVariation" id="prodVariation"></select>

            <label id="addOnsLabel" for="addons">Add Ons</label>
            <select name="addOn" id="addons"></select>

            <label id="drinkFlavourLabel" for="drinkFlavour">Drink Flavour</label>
            <select name="drinkFlavour" id="drinkFlavour"></select>

            <div id="quantity">
                <p>Quantity</p>
                <i onclick="decrease()" class='bx bxs-checkbox-minus'></i>
                <input type="number" name="prodQuantity" id="prodQuantity" value="1" min="0">
                <i onclick="increase()" class='bx bxs-plus-square'></i>
            </div>

            <p id="bottom">Total Price <input name="totalprice"
                    style="background-color:transparent; border: none; text-align:right;" id="totalprice" readonly></p>

            <div id="buttons">
                <button type="button" onclick="closeAddToCart()">Close</button>
                <input id="addbtn" type="submit" value="Add">
            </div>
        </form>
        <script>
            function calculateChange() {
                let totalBillStr = document.getElementById('totalbill').value;
                let totalBill = parseFloat(totalBillStr.replace('Rs', '').trim());
                let receivedBill = parseFloat(document.getElementById('recievecash').value);

                if (isNaN(totalBill) || isNaN(receivedBill)) {
                    return;
                }

                let change = receivedBill - totalBill;

                if (change < 0) {
                    document.getElementById('proceed').disabled = true;
                } else {
                    document.getElementById('proceed').disabled = false;
                }

                document.getElementById('change').value = change.toFixed(2);
            }
        </script>

    </main>
@endsection
