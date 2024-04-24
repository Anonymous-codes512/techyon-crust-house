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
                        <td>{{ $product->productName }}</td>
                        <td>
                            <a href="#" onclick="addRecipe()"><i class='bx bx-list-plus'></i></a>
                            {{-- <a href="#"><i class='bx bxs-edit-alt'></i></a> --}}
                            <a href="{{ route('deleteStock', $product->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="recipeOverlay"></div>
        <div class="recipePopup" id="recipePopup">
            <div id="recipepad">
                <h4>Product Recipe</h4>
                <div class="recipeContainer"></div>
                <div id="closebtn">
                    <button onclick="closeRecipe()">Close</button>
                </div>
            </div>
            <div id="recipelist">
                <h4>Avaliable Stock</h4>
                <div class="stockContainer">
                    @foreach ($stocks as $stock)
                        <p>{{ $stock->itemName }}</p>
                        <p>{{ $stock->itemName }}</p>
                        <p>{{ $stock->itemName }}</p>
                        <p>{{ $stock->itemName }}</p>
                        <p>{{ $stock->itemName }}</p>
                    @endforeach
                </div>
            </div>
        </div>

    </main>

    <script>
        function addRecipe() {
            let overlay = document.getElementById('recipeOverlay');
            let popup = document.getElementById('recipePopup');

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeRecipe() {
            let overlay = document.getElementById('recipeOverlay');
            let popup = document.getElementById('recipePopup');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }
    </script>
@endsection
