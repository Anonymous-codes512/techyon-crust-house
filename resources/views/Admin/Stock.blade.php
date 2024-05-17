@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/stock.css') }}">
@endpush

@section('main')
    <main id="stock">
        <div class="path">
            <p>Dashboard > Stocks</p>
        </div>

        <div class="newCategory">
            <button onclick="addStock()">Add New Stock</button>
        </div>

        @php
            $notification = $notification;
        @endphp

        @if ($notification)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('shownotify').click();
                });
            </script>
        @endif


        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Minimum Quantity</th>
                    <th>Unit price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockData as $stock)
                    <tr>
                        <td>{{ $stock->itemName }}</td>
                        <td>{{ $stock->itemQuantity }}</td>
                        <td>{{ $stock->mimimumItemQuantity }}</td>
                        <td>{{ $stock->unitPrice }}</td>
                        <td>
                            <a onclick="editStock({{ json_encode($stock) }})"><i class='bx bxs-edit-alt'></i></a>
                            <a href="{{ route('deleteStock', $stock->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <div class="pagination"> {{ $categories->links() }} </div> --}}


        {{--  
            |---------------------------------------------------------------|
            |================ Add new Stock Overlay ======================|
            |---------------------------------------------------------------|
        --}}

        <div id="overlay"></div>
        <form class="newstock" id="newStock" action="{{ route('createStock') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Add New Stock</h3>
            <hr>

            <div class="inputdivs">
                <input type="text" id="itemName" name="itemName" placeholder="Item Name" required>
            </div>

            <div class="inputdivs" id="unitsDiv">
                <input type="number" id="quantity" name="stockQuantity" placeholder="Stock Quantity" required>
                <select name="unit1" id="stockunit">
                    <option value="" selected disabled>Select unit</option>
                    <option value="mg">Milligram</option>
                    <option value="g">Gram</option>
                    <option value="Kg">Kilogram</option>
                    <option value="ml">Milliliter</option>
                    <option value="l">Liter</option>
                    <option value="Gallan">Gallan</option>
                </select>
            </div>

            <div class="inputdivs" id="unitsDiv">
                <input type="number" id="minquantity" name="minStockQuantity" placeholder="Minimum Stock Quantity"
                    required>
                <select name="unit2" id="minStockUnit">
                    <option value="" selected disabled>Select unit</option>
                    <option value="mg">Milligram</option>
                    <option value="g">Gram</option>
                    <option value="Kg">Kilogram</option>
                    <option value="ml">Milliliter</option>
                    <option value="l">Liter</option>
                    <option value="Gallan">Gallan</option>
                </select>
            </div>

            <div class="inputdivs">
                <input type="number" id="unitprice" name="unitPrice" placeholder="Unit Price" required>
            </div>

            <div class="btns">
                <button type="button" id="cancel" onclick="closeAddStock()">Cancel</button>
                <input type="submit" value="Add Stock">
            </div>

        </form>

        {{--  
            |---------------------------------------------------------------|
            |===================== Edit Stock Overlay ======================|
            |---------------------------------------------------------------|
        --}}

        <div id="editOverlay"></div>
        <form class="editstock" id="editStock" action="{{ route('updateStock') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Edit Stock</h3>
            <hr>

            <input type="hidden" id="sId" name="sId" required>

            <div class="inputdivs">
                <input type="text" id="iName" name="itemName" required>
            </div>

            <div class="inputdivs" id="unitsDiv">
                <input type="number" id="iQuantity" name="stockQuantity" required>
                <select name="unit1" id="iQUnit">
                    <option value="" selected disabled>Select unit</option>
                    <option value="mg">Milligram</option>
                    <option value="g">Gram</option>
                    <option value="Kg">Kilogram</option>
                    <option value="ml">Milliliter</option>
                    <option value="l">Liter</option>
                    <option value="Gallan">Gallan</option>
                </select>
            </div>

            <div class="inputdivs" id="unitsDiv">
                <input type="number" id="mQuantity" name="minStockQuantity" required>
                <select name="unit2" id="mQUnit">
                    <option value="" selected disabled>Select unit</option>
                    <option value="mg">Milligram</option>
                    <option value="g">Gram</option>
                    <option value="Kg">Kilogram</option>
                    <option value="ml">Milliliter</option>
                    <option value="l">Liter</option>
                    <option value="Gallan">Gallan</option>
                </select>
            </div>

            <div class="inputdivs">
                <input type="number" id="UPrice" name="unitPrice" required>
            </div>

            <div class="btns">
                <button type="button" id="cancel" onclick="closeEditStock()">Cancel</button>
                <input type="submit" value="Update">
            </div>
        </form>

        {{--  
            |---------------------------------------------------------------|
            |===================== Notification Popup ======================|
            |---------------------------------------------------------------|
        --}}

        <div id="notificationOverlay"></div>
        <div id="notification">
            <p>{{ $notification }}</p>
            <div>
                <button id="shownotify" type="button" style="display: none" onclick="showNotification()"></button>
                <button type="button" onclick="closeNotification()">Ok</button>
            </div>
        </div>

    </main>

    <script>
        function addStock() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newStock');

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeAddStock() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newStock');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function showNotification() {
            let overlay = document.getElementById('notificationOverlay');
            let popup = document.getElementById('notification');

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeNotification() {
            let overlay = document.getElementById('notificationOverlay');
            let popup = document.getElementById('notification');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function editStock(stock) {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStock');

            document.getElementById('sId').value = stock.id;
            document.getElementById('iName').value = stock.itemName;

            let quantity = '';
            let quantityUnit = '';
            if (stock.itemQuantity) {
                const quantityAndUnit = stock.itemQuantity.match(/(\d+)(\D+)/);
                if (quantityAndUnit && quantityAndUnit.length > 1) {
                    quantity = parseFloat(quantityAndUnit[1]);
                }
                if (quantityAndUnit && quantityAndUnit.length > 2) {
                    quantityUnit = quantityAndUnit[2];
                }
            }
            document.getElementById('iQuantity').value = quantity;
            document.getElementById('iQUnit').value = quantityUnit;

            let minQuantity = '';
            let minQuantityUnit = '';
            if (stock.mimimumItemQuantity) {
                const minQuantityAndUnit = stock.mimimumItemQuantity.match(/(\d+)(\D+)/);
                if (minQuantityAndUnit && minQuantityAndUnit.length > 1) {
                    minQuantity = parseFloat(minQuantityAndUnit[1]);
                }
                if (minQuantityAndUnit && minQuantityAndUnit.length > 2) {
                    minQuantityUnit = minQuantityAndUnit[2];
                }
            }
            document.getElementById('mQuantity').value = minQuantity;
            document.getElementById('mQUnit').value = minQuantityUnit;

            document.getElementById('UPrice').value = parseFloat(stock.unitPrice);

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeEditStock() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStock');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }
    </script>
@endsection


{{-- let iQUnit = stock.itemQuantity.replace(/[0-9.]/g, '');
    let mQUnit = stock.mimimumItemQuantity.replace(/[0-9.]/g, '');

    if (iQUnit == 'kg') {
        let itemQuantity = (parseFloat(stock.itemQuantity) < 1000) ? (parseFloat(stock.itemQuantity) * 1000): (parseFloat(stock.itemQuantity));
        document.getElementById('iQuantity').value = itemQuantity;
        document.getElementById('iQUnit').value = itemQuantity <= 1000 ? "Kg" : "g";
        let mimimumItemQuantity = (parseFloat(stock.mimimumItemQuantity) > 1000) ? (parseFloat(stock.mimimumItemQuantity) * 1000) : (parseFloat(stock.mimimumItemQuantity));
        document.getElementById('mQuantity').value = mimimumItemQuantity;
        document.getElementById('mQUnit').value = mimimumItemQuantity >= 1000 ? "kg" : "g";
        
    } else if (iQUnit == 'ltr') {
        let itemQuantity = (parseFloat(stock.itemQuantity) < 1000) ? (parseFloat(stock.itemQuantity) * 1000): (parseFloat(stock.itemQuantity));
        document.getElementById('iQuantity').value = itemQuantity;
        document.getElementById('iQUnit').value = itemQuantity <= 1000 ? "ltr" : "ml";
        let mimimumItemQuantity = (parseFloat(stock.mimimumItemQuantity) > 1000) ? (parseFloat(stock.mimimumItemQuantity) * 1000) : (parseFloat(stock.mimimumItemQuantity));
        document.getElementById('mQuantity').value = mimimumItemQuantity;
        document.getElementById('mQUnit').value = mimimumItemQuantity >= 1000 ? "ltr" : "ml";            
} --}}
