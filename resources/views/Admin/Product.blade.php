@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/product.css') }}">
@endpush

@section('main')
    <main id="product">
        <div class="path">
            <p>Dashboard > Products</p>
        </div>

        <div class="newCategory">
            <button onclick="addProduct()">Add New Product</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Quantity</th>
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
                        <td>{{ $product->productSize }}</td>
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
                <select name="categoryId" id="category" onclick="updateProductSizeDropdown()">
                    <option value="none" selected disabled>Select Product Category</option>
                    @foreach ($categoryData as $category)
                        <option value="{{ $category->id }},{{ $category->categoryName }}">{{ $category->categoryName }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('category')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <input type="text" id="productName" name="productName" placeholder="Product Name" required>
            </div>
            @error('productName')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <select name="productSize" id="productSize"></select>
            </div>
            @error('productSize')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <input type="number" id="price" name="productPrice" placeholder="Product Price" required>
            </div>
            @error('price')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <label for="upload-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-file" name="productImage" accept=".jpg,.jpeg,.png" required>
                    <p id="filename"></p>
                </label>
            </div>
            @error('productImage')
                <span class="error-message">{{ $message }}</span>
            @enderror

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

            <div class="inputdivs">
                <select name="editcategoryId" id="editcategory" onclick="updateProductSizeToEditDropdown()">
                    <option value="none" selected disabled>Select Product Category</option>
                    @foreach ($categoryData as $category)
                        <option value="{{ $category->id }},{{ $category->categoryName }}">{{ $category->categoryName }}
                        </option>
                    @endforeach
                </select>
            </div>

            @error('category')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <input type="hidden" id="pId" name="pId">

            <div class="inputdivs">
                <input type="text" id="pName" name="productName" placeholder="Product Name" required>
            </div>
            @error('productName')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <select name="editProductSize" id="editProductSize"></select>
            </div>

            @error('productSize')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <input type="number" id="pPrice" name="productPrice" placeholder="Product Price" required>
            </div>
            @error('price')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <label for="upload-update-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-update-file" name="productImage" accept=".jpg,.jpeg,.png" required>
                    <p id="namefile"></p>
                </label>
            </div>

            @error('productImage')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <div class="btns">
                <button id="cancel" onclick="closeEditCatogry()">Cancel</button>
                <input type="submit" value="Update">
            </div>
        </form>

    </main>

    <script>
        function addProduct() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newProduct');
            let productSizeDropdown = document.getElementById("productSize");
            productSizeDropdown.style.display = "none";

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeAddProduct() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newProduct');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function editProduct(Product) {
            console.log(Product);
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editProduct');

            document.getElementById('pId').value = Product.id;
            document.getElementById('pName').value = Product.productName;
            document.getElementById('pPrice').value = Product.productPrice;

            let categoryDropdown = document.getElementById('editcategory');
            for (let i = 0; i < categoryDropdown.options.length; i++) {
                if (categoryDropdown.options[i].text === Product.category_name) {
                    categoryDropdown.selectedIndex = i;
                    break;
                }
            }

            let productSizeDropdown = document.getElementById('editProductSize');
            for (let i = 0; i < productSizeDropdown.options.length; i++) {
                if (productSizeDropdown.options[i].text === Product.productSize) {
                    productSizeDropdown.selectedIndex = i;
                    break;
                }
            }

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeEditCatogry() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editProduct');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }


        function updateProductSizeDropdown() {
            let categoryField = document.getElementById("category").value.trim();
            let productSizeDropdown = document.getElementById("productSize");
            productSizeDropdown.innerHTML = "";

            let category = categoryField.split(',');

            if (category.length > 1 && category[1]) {
                if (category[1].trim().toLowerCase() === "drinks") {
                    productSizeDropdown.style.display = "block";
                    let drinkProductSizes = ["Regular", "1 Liter", "1.5 Liter"];
                    addOptionsToDropdown(drinkProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "appetizer") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["3 Pieces", "6 Pieces", "12 Pieces"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "fries") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["Regular", "Large"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "pizza") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["Small", "Regular", "Large", "Party"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "burger") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["Burger", "Fries + Reg Drink"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "others") {
                    productSizeDropdown.style.display = "none";
                } else {
                    let foodProductSizes = ["Small", "Medium", "Large", "Extra Large", "Jumbo"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                }
            }
        }

        function updateProductSizeToEditDropdown() {
            let categoryField = document.getElementById("editcategory").value.trim();
            let productSizeDropdown = document.getElementById("editProductSize");
            productSizeDropdown.innerHTML = "";

            let category = categoryField.split(',');

            if (category.length > 1 && category[1]) {
                if (category[1].trim().toLowerCase() === "drinks") {
                    productSizeDropdown.style.display = "block";
                    let drinkProductSizes = ["Regular", "1 Liter", "1.5 Liter"];
                    addOptionsToDropdown(drinkProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "appetizer") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["3 Pieces", "6 Pieces", "12 Pieces"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "fries") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["Regular", "Large"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "pizza") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["Small", "Regular", "Large", "Party"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "burger") {
                    productSizeDropdown.style.display = "block";
                    let foodProductSizes = ["Burger", "Fries + Reg Drink"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                } else if (category[1].trim().toLowerCase() === "others") {
                    productSizeDropdown.style.display = "none";
                } else {
                    let foodProductSizes = ["Small", "Medium", "Large", "Extra Large", "Jumbo"];
                    addOptionsToDropdown(foodProductSizes, productSizeDropdown);
                }
            }
        }

        function addOptionsToDropdown(optionsArray, dropdown) {
            let defaultOption = document.createElement("option");
            defaultOption.disabled = true;
            defaultOption.selected = true;
            defaultOption.text = "Select Product Size";
            dropdown.add(defaultOption);

            for (let i = 0; i < optionsArray.length; i++) {
                let option = document.createElement("option");
                option.text = optionsArray[i];
                option.value = optionsArray[i];
                dropdown.add(option);
            }
        }


        const uploadUpdatedFile = document.getElementById('upload-update-file');
        const filenamSpan = document.getElementById('namefile');
        uploadUpdatedFile.addEventListener('change', function(e) {
            const fileNam = this.value.split('\\').pop();
            filenamSpan.textContent = fileNam ? fileNam : 'No file chosen';
        });


        const uploadFile = document.getElementById('upload-file');
        const filenameSpan = document.getElementById('filename');
        uploadFile.addEventListener('change', function(e) {
            const fileName = this.value.split('\\').pop();
            filenameSpan.textContent = fileName ? fileName : 'No file chosen';
        });
    </script>
@endsection
