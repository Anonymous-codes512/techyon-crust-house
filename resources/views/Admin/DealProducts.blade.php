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
                    <p>{{ $product->category_name }}</p>
                    <p>{{ $product->productName }}</p>
                    <p>{{ $product->productSize }}</p>
                    <p>{{ $product->productPrice }} Pkr</p>
                </div>
            @endforeach
        </section>
        @php
            $id = session('id');
        @endphp
        <form action="{{ route('createDealProducts') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" id="products" name="dealProducts">
            <input type="submit" id="adddeal" value="Add Product to Deal">
        </form>
    </main>
    <script>

        function toggleProductSelection(element) {
            let productName = element.querySelector('p').textContent;
            let inputField = document.getElementById('products');

            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                inputField.value = inputField.value.replace(productName + ',', '');
            } else {
                element.classList.add('selected');
                inputField.value += productName + ',';
            }
        }

        // function showPrompt() {
        //     let overlay = document.getElementById('Overlay');
        //     let prompt = document.getElementById('extrainfo');

        //     overlay.style.display = 'block';
        //     prompt.style.display = 'flex';
        // }

        // function closePrompt() {
        //     let overlay = document.getElementById('Overlay');
        //     let prompt = document.getElementById('extrainfo');
        //     let selectedProduct = document.querySelector('.imgbox.selected'); // Get the selected product

        //     overlay.style.display = 'none';
        //     prompt.style.display = 'none';

        //     if (selectedProduct) {
        //         element.classList.remove('selected');
        //         inputField.value = inputField.value.replace(productName + ',', '');
        //     } else {
        //         element.classList.add('selected');
        //         inputField.value += productName + ',';
        //     }
        // }

        // function addDetails() {
        //     closePrompt();
        // }
    </script>
@endsection
