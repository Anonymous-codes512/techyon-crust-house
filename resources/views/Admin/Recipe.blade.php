@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/recipe.css') }}">
@endpush

@section('main')
    <main id="recipePage">
        <div class="path">
            <p>Dashboard > Recipe</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product Category</th>
                    <th>Product Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->category_name }}</td>
                        <td>{{ $product->productSize }} {{ $product->productName }}</td>
                        <td>
                            <a href="#"
                                onclick="addRecipe('{{ $product->productSize }}', '{{ $product->productName }}', '{{ $product->id }}')"><i
                                    class='bx bx-list-plus'></i></a>
                            <a href="{{ route('deleteStock', $product->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="recipeOverlay"></div>
        <div class="recipePopup" id="recipePopup">
            <form id="recipepad" action="{{ route('createRecipe') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h5 id="prod_name"></h5>
                <input type="hidden" name="pId" id="pID">
                <textarea class="recipeContainer" name="productRecipe" style="resize: none; cursor :pointer;" readonly></textarea>
                <div id="buttons">
                    <button onclick="closeRecipe()">Close</button>
                    <input type="submit" value="Add Recipe">
                </div>

            </form>
            <div id="recipelist">
                <h4>Avaliable Stock</h4>
                <div class="searchBar">
                    <input type="text" id="Search" placeholder="Search Item in Stock">
                    <i class='bx bx-search'></i>
                </div>
                <div class="stockContainer">
                    @foreach ($stocks as $stock)
                        <p onclick="handleStockItemClick('{{ $stock->itemName }}')">{{ $stock->itemName }}</p>
                    @endforeach
                </div>
            </div>
        </div>

    </main>

    <script>
        let product_name;

        function addRecipe(quantity, productName, id) {
            let overlay = document.getElementById('recipeOverlay');
            let popup = document.getElementById('recipePopup');
            let pId = document.getElementById('pID');
            let pname = document.getElementById('prod_name');

            pname.textContent = quantity + " " + productName + " Recipe";
            pId.value = id;

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeRecipe() {
            let recipeContainer = document.querySelector('.recipeContainer');
            while (recipeContainer.firstChild) {
                recipeContainer.removeChild(recipeContainer.firstChild);
            }

            let overlay = document.getElementById('recipeOverlay');
            let popup = document.getElementById('recipePopup');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function addItemToRecipe(itemName, quantity) {
            const recipeContainer = document.querySelector('.recipeContainer');
            const existingItems = recipeContainer.value;

            if (existingItems.includes(itemName)) {
                alert('Item already exists in the recipe.');
                return;
            }
            recipeContainer.value += quantity + ' ' + itemName + '\n';
        }


        function handleStockItemClick(itemName) {
            const quantity = prompt(`Enter quantity for ${itemName}:`);
            if (quantity !== null && quantity !== '') {
                addItemToRecipe(itemName, quantity);
            }
        }

        const searchBar = document.getElementById('Search');
        const stockItems = document.querySelectorAll('.stockContainer p');
        searchBar.addEventListener('input', function() {
            const searchText = searchBar.value.toLowerCase();
            stockItems.forEach(item => {
                const itemName = item.textContent.toLowerCase();
                if (itemName.includes(searchText)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
@endsection
