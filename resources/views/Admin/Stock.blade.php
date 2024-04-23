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
                <select name="unit" id="stockunit">
                    <option value="" selected disabled>Select unit</option>
                    <option value="g">Gram</option>
                    <option value="ml">Milliliter</option>
                </select>
            </div>

            <div class="inputdivs" id="unitsDiv">
                <input type="number" id="minquantity" name="minStockQuantity" placeholder="Minimum Stock Quantity"
                    required>
                <select name="unit" id="minStockUnit">
                    <option value="" selected disabled>Select unit</option>
                    <option value="g">Gram</option>
                    <option value="ml">Milliliter</option>
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
                <select name="unit" id="iQUnit">
                    <option value="g">Gram</option>
                    <option value="ml">milliliter</option>
                </select>
            </div>

            <div class="inputdivs" id="unitsDiv">
                <input type="number" id="mQuantity" name="minStockQuantity" required>
                <select name="unit" id="mQUnit">
                    <option value="g">Gram</option>
                    <option value="ml">milliliter</option>
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

        function editStock(stock) {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStock');
            overlay.style.display = 'block';
            popup.style.display = 'flex';

            document.getElementById('sId').value = stock.id;
            document.getElementById('iName').value = stock.itemName;

            let iQUnit = stock.itemQuantity.replace(/[0-9.]/g, '');
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
            }
            document.getElementById('UPrice').value = parseFloat(stock.unitPrice);
        }

        function closeEditStock() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStock');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }
    </script>
@endsection
