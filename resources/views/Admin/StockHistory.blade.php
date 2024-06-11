@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/stock.css') }}">
@endpush

@section('main')
    <main id="stock">
        <div class="path">
            <p>Dashboard > Stocks</p>
        </div>

        @php
            $stockHistory = $stockHistory;
        @endphp

        <table id="stocksTable">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Minimum Quantity</th>
                    <th>Unit price</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockHistory as $stock)
                    
                    @php
                        $date_time = $stock->created_at;
                        $date = date('F d, Y', strtotime($date_time));
                        $time = date('g:i A', strtotime($date_time));
                    @endphp

                    <tr>
                        <td>{{ $stock->itemName }}</td>
                        <td>{{ $stock->itemQuantity }}</td>
                        <td>{{ $stock->mimimumItemQuantity }}</td>
                        <td>{{ $stock->unitPrice }}</td>
                        <td>{{ $date }}</td>
                        <td>{{ $time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
