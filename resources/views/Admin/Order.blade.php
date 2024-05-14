@extends('Components.Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/order.css') }}">
@endpush

@section('main')
    <main id="order">
        <div class="path">
            <p>Dashboard > Orders</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product Category</th>
                    <th>Product Name</th>
                    <th>Product Quantity</th>
                    <th>Price</th>
                    <th>Salesman</th>
                    <th>Chef</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
    </main>

    <script>
        let texts = document.getElementsByClassName('status');
        Array.from(texts).forEach(text => {
            if (text.textContent.toLowerCase() === "accept") {
                text.style.color = '#3FC28A';
            } else if (text.textContent.toLowerCase() === "reject") {
                text.style.color = '#F45B69';
            }
        });
    </script>
@endsection
