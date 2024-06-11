@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/product.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('JavaScript\product.js') }}"></script>
@endpush
@section('main')
    <main id="product">
        <div class="path">
            <p>Dashboard > Products</p>
        </div>

        <div class="newCategory">
            <button onclick="addProduct()">Add New Product</button>
        </div>

        @php
            $productsData = $productsData;
        @endphp

        <table id="productTable">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Variation</th>
                    <th>Product Price</th>
                    <th>Product Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productsData as $product)
                    <tr>
                        <td><img src={{ asset('Images/ProductImages/' . $product->productImage) }} alt="Image"></td>
                        <td>{{ $product->productName }}</td>
                        <td>{{ $product->productVariation }}</td>
                        <td>{{ $product->productPrice }} Pkr</td>
                        <td>{{ $product->category_name }}</td>
                        <td>
                            <a onclick="editProduct({{ json_encode($product) }})"><i class='bx bxs-edit-alt'></i></a>
                            <a href="{{ route('deleteProduct', $product->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <div class="pagination"> {{ $categories->links() }} </div> --}}


        {{--  
            |---------------------------------------------------------------|
            |================ Add new Product Overlay ======================|
            |---------------------------------------------------------------|
        --}}

        <div id="overlay"></div>
        <form class="newproduct" id="newProduct" action="{{ route('createProduct') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Add New Product</h3>
            <hr>

            <div class="inputdivs">
                <label for="category">Select category</label>
                <select name="categoryId" id="category">
                    <option value="none" selected disabled>Select Product Category</option>
                    @foreach ($categoryData as $category)
                        <option value="{{ $category->id }},{{ $category->categoryName }}">{{ $category->categoryName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="inputdivs inputProductName">
                <div class="ProductName">
                    <label for="productName">Product Name</label>
                    <input type="text" id="productName" name="productName" placeholder="Product Name" required>
                </div>
                <div class="ProductVariation">
                    <label for="noOfVariations">Variations</label>
                    <input type="number" id="noOfVariations" name="noOfVariations"
                        oninput="updateVariationFields(this.value)" placeholder="Ex 4 etc">
                </div>
            </div>

            <div id="variationsGroup">

            </div>

            <div class="inputdivs">
                <label for="upload-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-file" name="productImage" accept=".jpg,.jpeg,.png" required>
                    <p id="filename"></p>
                </label>
            </div>

            <div class="btns">
                <button id="cancel" onclick="closeAddProduct()">Cancel</button>
                <input type="submit" value="Add">
            </div>

        </form>

        {{--  
            |---------------------------------------------------------------|
            |=================== Edit Product Overlay ======================|
            |---------------------------------------------------------------|
        --}}

        <div id="editOverlay"></div>
        <form class="editproduct" id="editProduct" action="{{ route('updateProduct') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Edit Product</h3>
            <hr>

            <input type="hidden" id="pId" name="pId">

            <div class="inputdivs">
                <label for="pName">Product Name</label>
                <input type="text" id="pName" name="productName" placeholder="Product Name" required>
            </div>

            <div class="inputdivs inputProductName">
                <div class="ProductName">
                    <label for="editproductName">Product Variation</label>
                    <input type="text" id="editProductVariation" name="productVariation" required>
                </div>
                <div class="ProductVariation">
                    <label for="editVariationPrice">Price</label>
                    <input type="number" id="editVariationPrice" name="Price">
                </div>
            </div>

            <div class="inputdivs">
                <label for="upload-update-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-update-file" name="productImage" accept=".jpg,.jpeg,.png">
                    <p id="namefile"></p>
                </label>
            </div>

            <div class="btns">
                <button type="button" id="cancel" onclick="closeEditCatogry()">Cancel</button>
                <input type="submit" value="Update">
            </div>
        </form>

    </main>

    <script>
        const Data = @json($productsData);
        const productName = Data.map(product => product.$productName);
        const SEARCHBAR = document.getElementById('search');

        function searchCategory() {
            let filter, table, tr, td, i, txtValue;
            filter = SEARCHBAR.value.toUpperCase();
            table = document.getElementById("productTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        SEARCHBAR.addEventListener('keyup', searchCategory);
    </script>
@endsection
