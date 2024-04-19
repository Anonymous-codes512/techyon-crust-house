@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/deal.css') }}">
@endpush

@section('main')
    <main id="dealProducts">
        <div class="path">
            <h2>Update products to Deal</h2>
        </div>

        <section class="products">
            @foreach ($Products as $product)
                <div class="imgbox" onclick="toggleProductSelection(this)">
                    <img src="{{ asset('Images/ProductImages/' . $product->productImage) }}" alt="Product">
                    <p>{{ $product->productName }}</p>
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
        // JavaScript function to toggle product selection
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
    </script>
@endsection
