@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/recipe.css') }}">
@endpush

@section('main')
    <main id="recipePage">
        <div class="path">
            <p>Dashboard > Recipe</p>
        </div>

        @php
            $products = $products;
            $categories = $categories;

        @endphp

        <div class="productCategory">
            @foreach ($categories as $category)
                <div class="categorydiv" id="showProductsInCategory"
                    onclick="productCategory({{ json_encode($category) }}, {{ json_encode($products) }})">
                    <div class="categoryImg">
                        <img src="{{ asset('Images/CategoryImages/' . $category->categoryImage) }}" alt="Category Image">
                    </div>
                    <div class="categoryDetails">
                        <h3>{{ $category->categoryName }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="categoryProductOverlay"></div>
        <div id="categoryProducts">
            <div class="table">
                <table id="productRecipeTable">
                    <thead>
                        <tr>
                            <th>Product Category</th>
                            <th>Product Name</th>
                            <th>Product Variation</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="body">
                    </tbody>
                </table>
            </div>
            <div class="btns">
                <button type="button" onclick="closeProductCategory()">Close</button>
                <button id="showproductRecipebutton" onclick="showProductRecipe()"style="display: none;"
                    type="button"></button>
            </div>
        </div>

        @if (session('showproductRecipe'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('showproductRecipebutton').click();
                });
            </script>
        @endif

        <div id="productRecipeOverlay"></div>
        <div id="productRecipe">
            <p id="productRecipeitems"></p>
            <div class="table">
                <table id="recipeTable">
                    <thead>
                        <tr>
                            <th>Recipe Item</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($recipes)
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const firstRecipe = @json($recipes->first());
                                    if (firstRecipe) {
                                        document.getElementById('productRecipeitems').textContent =
                                            `Recipe of ${firstRecipe.product.productName}`;
                                    }
                                });
                            </script>
                            @foreach ($recipes as $recipe)
                                <tr>
                                    <td>{{ $recipe->stock->itemName }}</td>
                                    <td>{{ $recipe->quantity }}</td>
                                    <td>
                                        <a href="{{ route('deleteStockFromRecipe', [$recipe->id, $recipe->category_id, $recipe->product_id]) }}"><i class='bx bxs-trash-alt'></i></a>
                                        {{-- <a href="{{ route('deleteStockFromRecipe', $recipe->id) }}"><i class='bx bxs-trash-alt'></i></a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No recipes found.</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
            </div>
            <div class="btns">
                <button type="button" onclick="closeProductRecipe()">Close</button>
            </div>
        </div>

        <div id="recipeOverlay"></div>
        <div class="recipePopup" id="recipePopup">
            <form id="recipepad" action="{{ route('createRecipe') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h5 id="prod_name"></h5>
                <input type="hidden" name="cId" id="cID">
                <input type="hidden" name="pId" id="pID">
                <p id="recipeContainer" class="recipeContainer" name="productRecipe"></p>
                <textarea id="recipeTextArea" name="recipeItems" style="display: none;"></textarea>
                <div id="buttons">
                    <button type="button" onclick="closeRecipe()">Close</button>
                    <input type="submit" value="Add Recipe">
                </div>

            </form>
            <div id="recipelist">
                <div class="searchBar">
                    <input type="text" id="Search" placeholder="Search Item in Stock">
                    <i class='bx bx-search'></i>
                </div>
                <div class="stockContainer">
                    @foreach ($stocks as $stock)
                        <p onclick="handleStockItemClick({{ json_encode($stock) }})">{{ $stock->itemName }}</p>
                    @endforeach
                </div>
            </div>
        </div>

    </main>

    <script>
        let product_name;

        function productCategory(category, products) {
            let overlay = document.getElementById('categoryProductOverlay');
            let popup = document.getElementById('categoryProducts');
            document.getElementById('cID').value = category.id;

            let selectedProducts = products.filter(product => product.category_id === category.id);
            selectedProducts.sort((a, b) => {
                if (a.productName < b.productName) return -1;
                if (a.productName > b.productName) return 1;
                if (a.productVariation < b.productVariation) return -1;
                if (a.productVariation > b.productVariation) return 1;
                return 0;
            });

            let tbody = document.getElementById('body');
            tbody.innerHTML = '';

            selectedProducts.forEach(product => {
                let newRow = document.createElement('tr');

                let productCategoryCell = document.createElement('td');
                productCategoryCell.textContent = category.categoryName;
                newRow.appendChild(productCategoryCell);

                let productNameCell = document.createElement('td');
                productNameCell.textContent = product.productName;
                newRow.appendChild(productNameCell);

                let variationCell = document.createElement('td');
                variationCell.textContent = product.productVariation;
                newRow.appendChild(variationCell);

                let actionCell = document.createElement('td');

                let addRecipeLink = document.createElement('a');
                addRecipeLink.setAttribute('href', '#');
                addRecipeLink.setAttribute('onclick',
                    `addRecipe('${product.productVariation}', '${product.productName}', '${product.id}')`);
                let addRecipeIcon = document.createElement('i');
                addRecipeIcon.setAttribute('class', 'bx bx-list-plus');
                addRecipeLink.appendChild(addRecipeIcon);
                actionCell.appendChild(addRecipeLink);

                const categoryId = category.id;
                const productId = product.id;
                let route = `{{ route('viewProductRecipe', [':categoryId', ':productId']) }}`;
                route = route.replace(':categoryId', categoryId).replace(':productId', productId);

                let showProductRecipe = document.createElement('a');
                showProductRecipe.setAttribute('href', route);
                let showIcon = document.createElement('i');
                showIcon.setAttribute('class', 'bx bx-show');
                showProductRecipe.appendChild(showIcon);
                actionCell.appendChild(showProductRecipe);

                newRow.appendChild(actionCell);

                tbody.appendChild(newRow);
            });

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeProductCategory() {
            let overlay = document.getElementById('categoryProductOverlay');
            let popup = document.getElementById('categoryProducts');
            overlay.style.display = 'none';
            popup.style.display = 'none';

        }

        function showProductRecipe() {
            const overlay = document.getElementById('productRecipeOverlay');
            const popup = document.getElementById('productRecipe');

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeProductRecipe() {
            let overlay = document.getElementById('productRecipeOverlay');
            let popup = document.getElementById('productRecipe');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function addRecipe(size, productName, id) {
            let overlay = document.getElementById('recipeOverlay');
            let popup = document.getElementById('recipePopup');
            let pId = document.getElementById('pID');
            let pname = document.getElementById('prod_name');

            pname.textContent = size + " " + productName + " Recipe";
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

        const recipeTextArea = document.getElementById('recipeTextArea');

        function addItemToRecipe(itemName, quantity, id) {
            const recipeContainer = document.querySelector('#recipeContainer');
            const newRecipeItem = document.createElement('p');
            newRecipeItem.style.margin = '2px';
            const itemText = `${quantity} ${itemName}`;
            const saveItem = `${quantity}~${id}`;

            newRecipeItem.textContent = itemText;

            if (recipeTextArea.value === '') {
                recipeTextArea.value = saveItem;
            } else {
                recipeTextArea.value += `, ${saveItem}`;
            }

            newRecipeItem.addEventListener('click', function() {
                newRecipeItem.remove();
                recipeTextArea.value = recipeTextArea.value.replace(`${saveItem}`, '');
                console.log(recipeTextArea.value);
            });

            recipeContainer.appendChild(newRecipeItem);
        }

        function handleStockItemClick(stock) {
            const quantity = prompt(`Enter quantity for ${stock.itemName}:`);
            if (quantity !== null && quantity !== '') {
                const formattedQuantity = formatQuantity(quantity);
                if (isValidQuantity(formattedQuantity)) {
                    addItemToRecipe(stock.itemName, formattedQuantity, stock.id);
                } else {
                    alert("Invalid quantity format. Please enter a valid quantity.");
                }
            }
        }

        function formatQuantity(quantity) {
            return quantity.replace(/(\d)([a-zA-Z])/g, '$1 $2');
        }

        function isValidQuantity(quantity) {
            const regex = /^\d+(\.\d+)?\s*(kg|g|mg|l|ml|gallon)$/i;
            return regex.test(quantity);
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
 