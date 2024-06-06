@extends('Components.Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/order.css') }}">
@endpush

@section('main')
    <main id="order">
        <div class="path">
            <p>Dashboard > Orders</p>
        </div>
        @php
            $order_id = 0;
        @endphp
        <table>
            <thead>
                <tr>
                    <th>Order id</th>
                    <th>Order Number</th>
                    <th>Total bill</th>
                    <th>Salesman</th>
                    {{-- <th>Chef</th> --}}
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="table-row-data">
                        <td onclick="window.location.href='{{ route('viewOrderProducts', $order->id) }}'">{{ $order->id }}
                        </td>
                        <td onclick="window.location.href='{{ route('viewOrderProducts', $order->id) }}'">
                            {{ $order->order_number }}</td>
                        <td onclick="window.location.href='{{ route('viewOrderProducts', $order->id) }}'">
                            {{ $order->total_bill }}</td>
                        <td onclick="window.location.href='{{ route('viewOrderProducts', $order->id) }}'">
                            {{ $order->salesman->name }}</td>

                        @if ($order->status == 1)
                            <td class="status" onclick="window.location.href='{{ route('viewOrderProducts', $order->id) }}'">
                                Completed</td>
                        @elseif ($order->status == 2)
                            <td class="status"
                                onclick="window.location.href='{{ route('viewOrderProducts', $order->id) }}'">
                                Pending</td>
                        @elseif ($order->status == 3)
                            <td class="status"
                                onclick="window.location.href='{{ route('viewOrderProducts', $order->id) }}'">
                                Canceled</td>
                        @endif

                        <td>
                            @if ($order->status == 1)
                                <a id="cancel-order" style="background-color:#4d4d4d; cursor: default;">Cancel</a>
                                @elseif($order->status == 2)
                                <a id="cancel-order" href="{{ route('cancelorder', $order->id) }}">Cancel</a>
                                @elseif($order->status == 3)
                                <a id="cancel-order" style="background-color:#4d4d4d;  cursor: default;">Cancel</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($orderItems != null)
            <div id="orderItemsOverlay"></div>
            <div id="orderItems">
                <div class="table">
                    <table id="itemtable" cellpadding="10">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $item)
                                @php
                                    $order_id = $item->order_id;
                                @endphp
                                <tr>
                                    <td>{{ $item->order_number }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->product_quantity }}</td>
                                    <td>{{ $item->total_price }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="btns">
                    <a href="{{ route('printrecipt', $order_id) }}"><button id="printbtn"
                            type="button">Print</button></a>
                    <a href="{{ route('viewOrdersPage') }}"><button id="closebtn" type="button">Close</button></a>
                </div>
            </div>
        @endif

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let orderStatus = document.getElementsByClassName('status');
            Array.from(orderStatus).forEach(status => {
                let statusText = status.textContent.toLowerCase().trim();
                if (statusText === "pending") {
                    status.style.color = '#ff9900';
                } else if (statusText === "completed") {
                    status.style.color = '#35b65b';
                } else if (statusText === "canceled" || statusText === "cancelled") {
                    status.style.color = '#F45B69';
                }
            });
        });
    </script>

@endsection
