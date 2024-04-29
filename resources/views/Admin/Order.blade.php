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
                    <th>Status</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Fries</td>
                    <td>Loaded Fries</td>
                    <td>2 Large</td>
                    <td>400 Pkr</td>
                    <td class="status">Accept</td>
                    {{-- <td class="order-status">
                        <a href="">Accept</a>
                        <a href="">Reject</a>
                    </td> --}}
                </tr>
                <tr>
                    <td>Pizza</td>
                    <td>Crown Crust</td>
                    <td>1 Jumbo</td>
                    <td>2500 Pkr</td>
                    <td class="status">Reject</td>
                    {{-- <td class="order-status">
                        <a href="">Accept</a>
                        <a href="">Reject</a>
                    </td> --}}
                </tr>
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
