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
            // $products = $products;
            $categories = $categories;
        @endphp

        <div class="productCategory">
            @foreach ($categories as $category)
                <div class="categorydiv" id="showProductsInCategory"
                    onclick="window.location='{{ route('showCategoryProducts', $category->id) }}'">
                    <div class="categoryImg">
                        <img src="{{ asset('Images/CategoryImages/' . $category->categoryImage) }}" alt="Category Image">
                    </div>
                    <div class="categoryDetails">
                        <h3>{{ $category->categoryName }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($categoryProducts != null)
            <div id="categoryProductOverlay" style="display: block;"></div>
            <div id="categoryProducts" style="display: flex;">
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
                        <tbody>
                            @foreach ($categoryProducts as $product)
                                <tr>
                                    <td>{{ $product->category_name }}</td>
                                    <td>{{ $product->productName }}</td>
                                    <td>{{ $product->productVariation }}</td>
                                    <td>
                                        <a href="#"><i onclick="addRecipe({{ json_encode($product) }})"
                                                class="bx bx-list-plus"></i></a>

                                        <a href="{{ route('viewProductRecipe', [$product->category_id, $product->id]) }}"><i
                                                onclick="showProductRecipe()" class="bx bx-show"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="btns">
                    <a href="{{ route('viewRecipePage') }}"><button type="button"
                            onclick="closeProductCategory()">Close</button></a>
                    <button id="showproductRecipebutton" onclick="showProductRecipe()"style="display: none;"
                        type="button"></button>
                </div>
            </div>
        @endif

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
                            {{-- @dd($recipes) --}}
                            {{-- <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const firstRecipe = @json($recipes->first());
                                    if (firstRecipe) {
                                        document.getElementById('productRecipeitems').textContent =
                                            `Recipe of ${firstRecipe.product.productName}`;
                                    }
                                });
                            </script> --}}
                            @foreach ($recipes as $recipe)
                                <tr>
                                    <td>{{ $recipe->stock->itemName }}</td>
                                    <td>{{ $recipe->quantity }}</td>
                                    <td>
                                        <a
                                            href="{{ route('deleteStockFromRecipe', [$recipe->id, $recipe->category_id, $recipe->product_id]) }}">
                                            <i class='bx bxs-trash-alt'></i>
                                        </a>
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

        <div id="addProductRecipeOverlay"></div>
        <div id="addProductRecipe">
            <h3>Add Recipe to Product</h3>
            <hr>

            <div class="inputdivs inputProductName" style="display: flex; margin:5px;">
                <div class="stockquantity">
                    <label for="item-name">Item Name</label>
                    <input type="text" id="item-name" placeholder="item Name" name="item-name" readonly required>
                </div>

                <div class="stockquantity">
                    <label for="item-stock-quantity">Item Stock Quantity</label>
                    <input type="text" id="item-stock-quantity" placeholder="123kg.." name="item-stock-quantity" readonly
                        required>
                </div>
            </div>

            <div class="inputdivs inputProductName" style="display: flex; margin:5px;">
                <div class="stockquantity">
                    <label for="item-quantity">Item Quantity</label>
                    <input type="number" id="item-quantity" placeholder="itemQuantity" name="itemQuantity" step="any"
                        min="0" required>
                </div>

                <div class="unitselection">
                    <label for="iQUnit">Unit</label>
                    <select name="unit1" id="iQUnit">
                        <option value="" selected disabled>Select unit</option>
                        <option value="mg">Milligram</option>
                        <option value="g">Gram</option>
                        <option value="kg">Kilogram</option>
                        <option value="ml">Milliliter</option>
                        <option value="liter">Liter</option>
                        <option value="gal">Gallon</option>
                        <option value="lbs">Pound</option>
                        <option value="oz">Ounce</option>
                    </select>
                </div>
            </div>

            <div class="btns">
                <input type="submit" value="Add" id="addItemButton">
                <button type="button" id="cancel">close</button>
            </div>

        </div>

    </main>

    <script>
        let product_name;
        // showProductsInCategory = document.getElementById('showProductsInCategory');

        // console.warn(showproductRecipebutton);
        // showProductsInCategory.addEventListener('click', () => {

        //     let overlay = document.getElementById('categoryProductOverlay');
        //     let popup = document.getElementById('categoryProducts');

        //     overlay.style.display = 'block';
        //     popup.style.display = 'flex';
        // });

        // document.getElementById('cID').value = category.id;

        // let selectedProducts = products.filter(product => product.category_id === category.id);
        // selectedProducts.sort((a, b) => {
        //     if (a.productName < b.productName) return -1;
        //     if (a.productName > b.productName) return 1;
        //     if (a.productVariation < b.productVariation) return -1;
        //     if (a.productVariation > b.productVariation) return 1;
        //     return 0;
        // });

        // let tbody = document.getElementById('body');
        // tbody.innerHTML = '';

        // selectedProducts.forEach(product => {
        //     let newRow = document.createElement('tr');

        //     let productCategoryCell = document.createElement('td');
        //     productCategoryCell.textContent = category.categoryName;
        //     newRow.appendChild(productCategoryCell);

        //     let productNameCell = document.createElement('td');
        //     productNameCell.textContent = product.productName;
        //     newRow.appendChild(productNameCell);

        //     let variationCell = document.createElement('td');
        //     variationCell.textContent = product.productVariation;
        //     newRow.appendChild(variationCell);

        //     let actionCell = document.createElement('td');

        //     let addRecipeLink = document.createElement('a');
        //     addRecipeLink.setAttribute('href', '#');
        //     addRecipeLink.setAttribute('onclick',
        //         `addRecipe('${product.productVariation}', '${product.productName}', '${product.id}')`);
        //     let addRecipeIcon = document.createElement('i');
        //     addRecipeIcon.setAttribute('class', 'bx bx-list-plus');
        //     addRecipeLink.appendChild(addRecipeIcon);
        //     actionCell.appendChild(addRecipeLink);

        //     const categoryId = category.id;
        //     const productId = product.id;
        //     let route = `{{ route('viewProductRecipe', [':categoryId', ':productId']) }}`;
        //     route = route.replace(':categoryId', categoryId).replace(':productId', productId);

        //     let showProductRecipe = document.createElement('a');
        //     showProductRecipe.setAttribute('href', route);
        //     let showIcon = document.createElement('i');
        //     showIcon.setAttribute('class', 'bx bx-show');
        //     showProductRecipe.appendChild(showIcon);
        //     actionCell.appendChild(showProductRecipe);

        //     newRow.appendChild(actionCell);

        //     tbody.appendChild(newRow);
        // });

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

        function addRecipe(product) {
            let overlay = document.getElementById('recipeOverlay');
            let popup = document.getElementById('recipePopup');
            let pId = document.getElementById('pID');
            let cID = document.getElementById('cID');
            let pname = document.getElementById('prod_name');

            pname.textContent = product.productVariation + " " + product.productName + " Recipe";
            pId.value = product.id;
            cID.value = product.category_id;

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

        // function addItemToRecipe(itemName, quantity, id) {
        //     const recipeContainer = document.querySelector('#recipeContainer');
        //     const newRecipeItem = document.createElement('p');
        //     newRecipeItem.style.margin = '2px';
        //     const itemText = `${quantity} ${itemName}`;
        //     const saveItem = `${quantity}~${id}`;

        //     newRecipeItem.textContent = itemText;

        //     if (recipeTextArea.value === '') {
        //         recipeTextArea.value = saveItem;
        //     } else {
        //         recipeTextArea.value += `, ${saveItem}`;
        //     }

        //     newRecipeItem.addEventListener('click', function() {
        //         newRecipeItem.remove();
        //         recipeTextArea.value = recipeTextArea.value.replace(`${saveItem}`, '');
        //         console.log(recipeTextArea.value);
        //     });

        //     recipeContainer.appendChild(newRecipeItem);
        // }

        // function handleStockItemClick(stock) {
        //     const quantity = prompt(`Enter quantity for ${stock.itemName}:`);
        //     if (quantity !== null && quantity !== '') {
        //         const formattedQuantity = formatQuantity(quantity);
        //         if (isValidQuantity(formattedQuantity)) {
        //             addItemToRecipe(stock.itemName, formattedQuantity, stock.id);
        //         } else {
        //             alert("Invalid quantity format. Please enter a valid quantity.");
        //         }
        //     }
        // }

        // function formatQuantity(quantity) {
        //     return quantity.replace(/(\d)([a-zA-Z])/g, '$1 $2');
        // }

        // function isValidQuantity(quantity) {
        //     const regex = /^\d+(\.\d+)?\s*(kg|g|mg|l|ml|gallon)$/i;
        //     return regex.test(quantity);
        // }

        function addItemToRecipe(itemName, quantity, unit, id) {
            const recipeContainer = document.querySelector('#recipeContainer');
            const recipeTextArea = document.querySelector('#recipeTextArea');

            function convertWeightToGrams(quantity, unit) {
                switch (unit) {
                    case 'kg':
                        return quantity * 1000;
                    case 'g':
                        return quantity;
                    case 'mg':
                        return quantity / 1000;
                    case 'lbs':
                        return quantity * 453.592;
                    case 'oz':
                        return quantity * 28.3495;
                    default:
                        return 0;
                }
            }

            function convertVolumeToMilliliters(quantity, unit) {
                switch (unit) {
                    case 'liter':
                        return quantity * 1000;
                    case 'ml':
                        return quantity;
                    case 'gal':
                        return quantity * 3785.41;
                    default:
                        return 0;
                }
            }

            function convertFromGrams(grams) {
                if (grams >= 1000) {
                    return {
                        quantity: grams / 1000,
                        unit: 'kg'
                    };
                } else if (grams >= 453.592) {
                    return {
                        quantity: grams / 453.592,
                        unit: 'lbs'
                    };
                } else if (grams >= 28.3495) {
                    return {
                        quantity: grams / 28.3495,
                        unit: 'oz'
                    };
                } else if (grams >= 1) {
                    return {
                        quantity: grams,
                        unit: 'g'
                    };
                } else {
                    return {
                        quantity: grams * 1000,
                        unit: 'mg'
                    };
                }
            }

            function convertFromMilliliters(milliliters) {
                if (milliliters >= 1000) {
                    return {
                        quantity: milliliters / 1000,
                        unit: 'liter'
                    };
                } else if (milliliters >= 3785.41) {
                    return {
                        quantity: milliliters / 3785.41,
                        unit: 'gal'
                    };
                } else {
                    return {
                        quantity: milliliters,
                        unit: 'ml'
                    };
                }
            }

            const isWeightUnit = ['kg', 'g', 'mg', 'lbs', 'oz'].includes(unit);
            const isVolumeUnit = ['liter', 'ml', 'gal'].includes(unit);

            let quantityInBaseUnit;
            if (isWeightUnit) {
                quantityInBaseUnit = convertWeightToGrams(parseFloat(quantity), unit);
            } else if (isVolumeUnit) {
                quantityInBaseUnit = convertVolumeToMilliliters(parseFloat(quantity), unit);
            } else {
                alert("Unsupported unit.");
                return;
            }

            const existingItem = Array.from(recipeContainer.querySelectorAll('.recipe-item')).find(item => {
                return item.dataset.id === String(id);
            });

            if (existingItem) {
                const currentQuantityInBaseUnit = parseFloat(existingItem.dataset.quantityBaseUnit);
                const newQuantityInBaseUnit = currentQuantityInBaseUnit + quantityInBaseUnit;
                let convertedQuantity;

                if (isWeightUnit) {
                    convertedQuantity = convertFromGrams(newQuantityInBaseUnit);
                } else if (isVolumeUnit) {
                    convertedQuantity = convertFromMilliliters(newQuantityInBaseUnit);
                }

                existingItem.textContent = `${convertedQuantity.quantity.toFixed(2)} ${convertedQuantity.unit} ${itemName}`;
                existingItem.dataset.quantityBaseUnit = newQuantityInBaseUnit;
                existingItem.dataset.quantity = convertedQuantity.quantity.toFixed(2);
                existingItem.dataset.unit = convertedQuantity.unit;

                const regex = new RegExp(`\\b${currentQuantityInBaseUnit}\\s+${existingItem.dataset.unit}~${id}\\b`);
                recipeTextArea.value = recipeTextArea.value.replace(regex,
                    `${newQuantityInBaseUnit} ${existingItem.dataset.unit === 'g' ? 'g' : 'ml'}~${id}`);
            } else {
                const newRecipediv = document.createElement('div');
                newRecipediv.style.display = 'flex';
                newRecipediv.style.alignItems = 'center';

                const newRecipeItem = document.createElement('p');
                newRecipeItem.classList.add('recipe-item');
                newRecipeItem.style.margin = '2px';
                newRecipeItem.style.width = '95%';
                newRecipeItem.textContent = `${quantity} ${unit} ${itemName}`;
                newRecipeItem.dataset.id = id;
                newRecipeItem.dataset.quantityBaseUnit = quantityInBaseUnit;
                newRecipeItem.dataset.quantity = quantity;
                newRecipeItem.dataset.unit = unit;

                const recipeRemoveBtn = document.createElement('i');
                recipeRemoveBtn.classList.add('bx', 'bx-x', 'remove-item');
                recipeRemoveBtn.style.fontSize = '1.2vw';
                recipeRemoveBtn.style.borderRadius = '50%';
                recipeRemoveBtn.style.marginRight = '5px'

                newRecipediv.appendChild(newRecipeItem);
                newRecipediv.appendChild(recipeRemoveBtn);
                recipeContainer.appendChild(newRecipediv);

                recipeRemoveBtn.addEventListener('click', function() {
                    newRecipediv.remove();
                    const regex = new RegExp(
                        `\\b${quantityInBaseUnit}\\s+${unit === 'g' || unit === 'kg' || unit === 'mg' || unit === 'lbs' || unit === 'oz' ? 'g' : 'ml'}~${id}\\b`
                    );
                    recipeTextArea.value = recipeTextArea.value.replace(regex, '');
                });

                if (recipeTextArea.value === '') {
                    recipeTextArea.value =
                        `${quantityInBaseUnit} ${unit === 'g' || unit === 'kg' || unit === 'mg' || unit === 'lbs' || unit === 'oz' ? 'g' : 'ml'}~${id}`;
                } else {
                    recipeTextArea.value +=
                        `, ${quantityInBaseUnit} ${unit === 'g' || unit === 'kg' || unit === 'mg' || unit === 'lbs' || unit === 'oz' ? 'g' : 'ml'}~${id}`;
                }
            }
        }

        function handleStockItemClick(stock) {
            const addProductRecipeOverlay = document.querySelector('#addProductRecipeOverlay');
            const addProductRecipe = document.querySelector('#addProductRecipe');
            const addItemButton = document.querySelector('#addItemButton');
            const cancelButton = document.querySelector('#cancel');
            const recipePopup = document.getElementById('recipePopup');
            let itemName = document.getElementById('item-name');
            let itemStockQuantity = document.getElementById('item-stock-quantity');
            const categoryProducts = document.getElementById('categoryProducts');

            addProductRecipeOverlay.style.display = 'block';
            addProductRecipe.style.display = 'flex';

            itemName.value = stock.itemName;
            itemStockQuantity.value = stock.itemQuantity;

            recipePopup.style.display = 'none';
            categoryProducts.style.display = 'none';

            addItemButton.onclick = function() {
    const quantityInput = document.getElementById('item-quantity');
    const unitSelect = document.querySelector('#iQUnit');
    const quantity = quantityInput.value;
    const unit = unitSelect.value;

    const enteredQuantity = parseFloat(quantity);
    const stockQuantity = parseFloat(itemStockQuantity.value);

    if (enteredQuantity > 0 && enteredQuantity <= stockQuantity) {
        // Quantity is valid, proceed with adding the item
        if (quantity && unit) {
            addItemToRecipe(stock.itemName, quantity, unit, stock.id);

            addProductRecipeOverlay.style.display = 'none';
            addProductRecipe.style.display = 'none';
            recipePopup.style.display = 'flex';
            categoryProducts.style.display = 'flex';

            quantityInput.value = '';
            unitSelect.value = '';
        } else {
            alert("Please enter a valid quantity and select a unit.");
        }
    } else {
        // Quantity is not valid, show an alert
        alert('Quantity must be greater than 0 and less than or equal to the stock quantity.');
    }

    // Attach the click event handler to cancelButton
    cancelButton.onclick = function() {
        addProductRecipeOverlay.style.display = 'none';
        addProductRecipe.style.display = 'none';
        recipePopup.style.display = 'flex';
        categoryProducts.style.display = 'flex';
    };
}

        }

        // function formatQuantity(quantity) {
        //     return parseInt(quantity, 10); // Assuming the quantity is an integer
        // }

        // function isValidQuantity(quantity) {
        //     return Number.isInteger(quantity) && quantity > 0;
        // }

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
