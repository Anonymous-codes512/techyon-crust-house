@extends('Components.Salesman')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Salesman/dashboard.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('JavaScript/salesman.js') }}"></script>
@endpush

@section('main')
    <main id="salesman">
        <div id="productsSide">
            <div id="category_bar">
                <a href="{{ route('salesman_dashboard') }}"> All</a>
                @foreach ($Categories as $category)
                    <a href="{{ route('salesman_dash', $category->categoryName) }}">{{ $category->categoryName }} </a>
                @endforeach
                <a href="{{ route('salesman_dash', 'Deals') }}"> Deals</a>
            </div>

            <div id="products">
                @php
                    $displayedProductNames = [];
                @endphp

                @if ($Products !== null)
                    
                    @foreach ($Products as $product)
                        @if (!in_array($product->productName, $displayedProductNames))
                            @php
                                $displayedProductNames[] = $product->productName;
                            @endphp

                            <div class="imgbox"
                                onclick="showAddToCart({{ json_encode($product) }}, {{ json_encode($Products) }})">
                                <img src="{{ asset('Images/ProductImages/' . $product->productImage) }}" alt="Product">
                                <p class="product_name">{{ $product->productName }}</p>
                                <p class="product_price">From Rs. {{ $product->productPrice }}</p>
                            </div>
                        @endif
                    @endforeach
                
                @elseif ($Deals !== null)
                
                    @foreach ($Deals as $deal)
                        <div class="imgbox" onclick="showAddToCart({{ json_encode($deal) }}, {{ json_encode($Deals) }})">
                            <img src="{{ asset('Images/DealImages/' . $deal->dealImage) }}" alt="Product">
                            <p class="product_name">{{ $deal->dealTitle }}</p>
                        </div>
                    @endforeach

                @endif
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

            <select name="addOn" id="addons">
            </select>

            <select name="prodVariation" id="prodVariation">
            </select>

            <div id="quantity">
                <p>Quantity</p>
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
@endsection