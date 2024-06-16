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
            $recipes = $recipes;
            $categories = $categories;
            $products = $categoryProducts;
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

        {{-- @if (session('editproductrecipie'))
            <script>
                alert('123')
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('add_recipe').click();
                });
            </script>
        @endif --}}

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
                                        <a id="add_recipe" href="#"><i
                                                onclick="addRecipe({{ json_encode($product) }}, {{ json_encode($recipes) }})"
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
                        <option value="dozan">Dozan</option>
                        <option value="piece">Piece</option>
                    </select>
                </div>
            </div>

            <div class="btns">
                <input type="submit" value="Add" id="addItemButton">
                <button type="button" id="cancel" onclick="closeHandleStockItemClick()">close</button>
            </div>

        </div>

        <div id="editProductRecipeOverlay"></div>
        <form id="editProductRecipe" action="{{ route('editProductRecipe') }}" method="POST"
            enctype="multipart/form-data" onsubmit="handleFormSubmit(event)">

            @csrf
            <h3>Edit Recipe</h3>
            <hr>
            <input type="hidden" id="recipeid" name="recipeId">
            <div class="inputdivs inputProductName" style="display: flex; margin:5px;">
                <div class="stockquantity">
                    <label for="item-name">Item Name</label>
                    <input type="text" id="edit-item-name" placeholder="item Name" name="item-name" readonly
                        required>
                </div>

                <div class="stockquantity">
                    <label for="item-stock-quantity">Item Stock Quantity</label>
                    <input type="text" id="edit-item-stock-quantity" placeholder="123kg.." name="item-stock-quantity"
                        required>
                </div>

                <div class="unitselection">
                    <label for="iQUnit">Unit</label>
                    <select name="unit1" id="editQUnit">
                        <option value="" selected disabled>Select unit</option>
                        <option value="mg">Milligram</option>
                        <option value="g">Gram</option>
                        <option value="kg">Kilogram</option>
                        <option value="ml">Milliliter</option>
                        <option value="liter">Liter</option>
                        <option value="gal">Gallon</option>
                        <option value="lbs">Pound</option>
                        <option value="oz">Ounce</option>
                        <option value="dozan">Dozan</option>
                        <option value="piece">Piece</option>
                    </select>
                </div>
            </div>

            <script>
                function hideFormPopup() {
                    document.getElementById('editProductRecipeOverlay').style.display = 'none';
                    document.getElementById('editProductRecipe').style.display = 'none';
                }

                function handleFormSubmit(event) {
                    event.preventDefault();
                    const formData = new FormData(event.target);
                    fetch(event.target.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(text => {
                            try {
                                const data = JSON.parse(text);
                                console.log('Form submitted successfully:', data);
                                hideFormPopup();
                                const product = {
                                    /* your product data */ };
                                const recipes = {
                                    /* your recipes data */ };
                                addRecipe(product, recipes);
                            } catch (error) {
                                console.error('Form submission error:', text);
                            }
                        })
                        .catch(error => {
                            console.error('Form submission error:', error);
                        });
                }
            </script>

            <div class="btns">
                <input type="submit" value="Update">
                <button type="button" id="cancel" onclick="closeEditProductRecipe()">close</button>
            </div>
        </form>

    </main>

    <script>
        let product_name;

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

        // function addRecipe(product, recipes) {
        //     console.log(product)
        //     console.log(recipes)
        //     let overlay = document.getElementById('recipeOverlay');
        //     let popup = document.getElementById('recipePopup');
        //     let pId = document.getElementById('pID');
        //     let cID = document.getElementById('cID');
        //     let pname = document.getElementById('prod_name');

        //     pname.textContent = product.productVariation + " " + product.productName + " Recipe";
        //     pId.value = product.id;
        //     cID.value = product.category_id;

        //     overlay.style.display = 'block';
        //     popup.style.display = 'flex';
        // }

        function addRecipe(product, recipes) {
            let overlay = document.getElementById('recipeOverlay');
            let popup = document.getElementById('recipePopup');
            let pId = document.getElementById('pID');
            let cID = document.getElementById('cID');
            let pname = document.getElementById('prod_name');
            let recipeTextArea = document.getElementById('recipeTextArea');

            pname.textContent = product.productVariation + " " + product.productName + " Recipe";
            pId.value = product.id;
            cID.value = product.category_id;

            overlay.style.display = 'block';
            popup.style.display = 'flex';

            recipes.forEach(recipe => {
                if (product.id === recipe.product_id) {
                    const match = recipe.quantity.match(/^([\d.]+)\s*(.*)$/);

                    const quantity = match[1];
                    const Unit = match[2];
                    const itemName = recipe.stock.itemName;
                    const id = recipe.stock_id;

                    const Recipediv = document.createElement('div');
                    Recipediv.style.display = 'flex';
                    Recipediv.style.alignItems = 'center';

                    const existingRecipeItem = document.createElement('p');
                    existingRecipeItem.classList.add('recipe-item');
                    existingRecipeItem.style.margin = '2px';
                    existingRecipeItem.style.width = '95%';
                    existingRecipeItem.textContent = `${recipe.quantity} ${itemName}`;
                    existingRecipeItem.dataset.id = id;
                    existingRecipeItem.dataset.quantity = quantity;

                    const recipeEditBtn = document.createElement('i');
                    recipeEditBtn.classList.add('bx', 'bx-edit-alt', 'edit-item');
                    recipeEditBtn.style.fontSize = '1vw';
                    recipeEditBtn.style.padding = '5px';
                    recipeEditBtn.style.borderRadius = '50%';
                    recipeEditBtn.style.marginRight = '5px'

                    const recipeRemoveBtn = document.createElement('i');
                    recipeRemoveBtn.classList.add('bx', 'bx-x', 'remove-item');
                    recipeRemoveBtn.style.fontSize = '1.2vw';
                    recipeRemoveBtn.style.borderRadius = '50%';
                    recipeRemoveBtn.style.marginRight = '5px';

                    Recipediv.appendChild(existingRecipeItem);
                    Recipediv.appendChild(recipeEditBtn);
                    Recipediv.appendChild(recipeRemoveBtn);
                    recipeContainer.appendChild(Recipediv);

                    recipeEditBtn.addEventListener('click', function() {
                        updateproductRecipe(itemName, quantity, Unit, recipe.id);
                    });

                    recipeRemoveBtn.addEventListener('click', function() {
                        newRecipediv.remove();
                        const itemToRemove = `${quantity} ${Unit}~${id}, `;
                        recipeTextArea.value = recipeTextArea.value.replace(itemToRemove, '');
                    });
                    if (recipeTextArea.value === '') {
                        recipeTextArea.value = `${quantity} ${Unit}~${id}`;
                    } else {
                        recipeTextArea.value += `, ${quantity} ${Unit}~${id}`;
                    }
                }
            });
        }

        function updateproductRecipe(itemName, quantity, Unit, id) {
            let overlay = document.getElementById('editProductRecipeOverlay');
            let popup = document.getElementById('editProductRecipe');

            const recipePopup = document.getElementById('recipePopup');
            const categoryProducts = document.getElementById('categoryProducts');

            document.getElementById('edit-item-name').value = itemName;
            document.getElementById('edit-item-stock-quantity').value = quantity;
            document.getElementById('editQUnit').value = Unit;
            document.getElementById('recipeid').value = id;

            recipePopup.style.display = 'none';
            categoryProducts.style.display = 'none';

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeEditProductRecipe() {
            let overlay = document.getElementById('editProductRecipeOverlay');
            let popup = document.getElementById('editProductRecipe');
            const recipePopup = document.getElementById('recipePopup');
            const categoryProducts = document.getElementById('categoryProducts');

            recipePopup.style.display = 'flex';
            categoryProducts.style.display = 'flex';
            overlay.style.display = 'none';
            popup.style.display = 'none';
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

            const isWeightUnit = ['kg', 'g', 'mg', 'lbs', 'oz', 'piece', 'dozan'].includes(unit);
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
                newRecipeItem.style.width = '90%';
                newRecipeItem.textContent = `${quantity} ${unit} ${itemName}`;
                newRecipeItem.dataset.id = id;
                newRecipeItem.dataset.quantityBaseUnit = quantityInBaseUnit;
                newRecipeItem.dataset.quantity = quantity;
                newRecipeItem.dataset.unit = unit;

                const recipeEditBtn = document.createElement('i');
                recipeEditBtn.classList.add('bx', 'bx-edit-alt', 'edit-item');
                recipeEditBtn.style.fontSize = '1.2vw';
                recipeEditBtn.style.borderRadius = '50%';
                recipeEditBtn.style.marginRight = '5px'

                const recipeRemoveBtn = document.createElement('i');
                recipeRemoveBtn.classList.add('bx', 'bx-x', 'remove-item');
                recipeRemoveBtn.style.fontSize = '1.2vw';
                recipeRemoveBtn.style.borderRadius = '50%';
                recipeRemoveBtn.style.marginRight = '5px'

                newRecipediv.appendChild(newRecipeItem);
                newRecipediv.appendChild(recipeEditBtn);
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
            console.log(recipeTextArea.value);
        }

        function closeHandleStockItemClick() {
            const addProductRecipeOverlay = document.querySelector('#addProductRecipeOverlay');
            const addProductRecipe = document.querySelector('#addProductRecipe');
            const recipePopup = document.getElementById('recipePopup');
            const categoryProducts = document.getElementById('categoryProducts');

            addProductRecipeOverlay.style.display = 'none';
            addProductRecipe.style.display = 'none';
            recipePopup.style.display = 'flex';
            categoryProducts.style.display = 'flex';
        }

        function handleStockItemClick(stock) {
            const addProductRecipeOverlay = document.querySelector('#addProductRecipeOverlay');
            const addProductRecipe = document.querySelector('#addProductRecipe');
            const addItemButton = document.querySelector('#addItemButton');
            const cancelButton = document.querySelector('#cancel');
            let itemName = document.getElementById('item-name');
            let itemStockQuantity = document.getElementById('item-stock-quantity');
            const recipePopup = document.getElementById('recipePopup');
            const categoryProducts = document.getElementById('categoryProducts');

            addProductRecipeOverlay.style.display = 'block';
            addProductRecipe.style.display = 'flex';

            itemName.value = stock.itemName;
            itemStockQuantity.value = stock.itemQuantity; // Assuming stock has itemQuantity with unit

            recipePopup.style.display = 'none';
            categoryProducts.style.display = 'none';

            addItemButton.onclick = function() {
                const quantityInput = document.getElementById('item-quantity');
                const unitSelect = document.querySelector('#iQUnit');
                const enteredQuantity = parseFloat(quantityInput.value);
                const enteredUnit = unitSelect.value;

                const [stockQuantity, stockUnit] = parseQuantityAndUnit(stock.itemQuantity);
                const convertedQuantity = convertToStockUnit(enteredQuantity, enteredUnit, stockUnit);

                if (convertedQuantity > 0 && convertedQuantity <= stockQuantity) {
                    if (enteredQuantity && enteredUnit) {
                        addItemToRecipe(stock.itemName, enteredQuantity, enteredUnit, stock.id);

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
                    alert('Quantity must be greater than 0 and less than or equal to the stock quantity.');
                }
            }

            cancelButton.onclick = function() {
                addProductRecipeOverlay.style.display = 'none';
                addProductRecipe.style.display = 'none';
                recipePopup.style.display = 'flex';
                categoryProducts.style.display = 'flex';
            };
        }

        function parseQuantityAndUnit(quantityWithUnit) {
            const regex = /^([0-9.]+)\s*([a-zA-Z]+)$/;
            const match = quantityWithUnit.match(regex);

            if (match) {
                const quantity = parseFloat(match[1]);
                const unit = match[2];
                return [quantity, unit];
            } else {
                throw new Error('Invalid quantity and unit format');
            }
        }

        function convertToStockUnit(quantity, fromUnit, toUnit) {
            const conversionRates = {
                'mg': {
                    'mg': 1,
                    'g': 0.001,
                    'kg': 0.000001,
                    'ml': 0.001,
                    'liter': 0.000001,
                    'gal': 0.000000264172,
                    'lbs': 0.00000220462,
                    'oz': 0.000035274
                },
                'g': {
                    'mg': 1000,
                    'g': 1,
                    'kg': 0.001,
                    'ml': 1,
                    'liter': 0.001,
                    'gal': 0.000264172,
                    'lbs': 0.00220462,
                    'oz': 0.035274
                },
                'kg': {
                    'mg': 1000000,
                    'g': 1000,
                    'kg': 1,
                    'ml': 1000,
                    'liter': 1,
                    'gal': 0.264172,
                    'lbs': 2.20462,
                    'oz': 35.274
                },
                'ml': {
                    'mg': 1,
                    'g': 0.001,
                    'kg': 0.000001,
                    'ml': 1,
                    'liter': 0.001,
                    'gal': 0.000264172,
                    'lbs': 0.00220462,
                    'oz': 0.035274
                },
                'liter': {
                    'mg': 1000000,
                    'g': 1000,
                    'kg': 1,
                    'ml': 1000,
                    'liter': 1,
                    'gal': 0.264172,
                    'lbs': 2.20462,
                    'oz': 35.274
                },
                'gal': {
                    'mg': 3785411.78,
                    'g': 3785.41,
                    'kg': 3.78541,
                    'ml': 3785.41,
                    'liter': 3.78541,
                    'gal': 1,
                    'lbs': 8.3454,
                    'oz': 128
                },
                'lbs': {
                    'mg': 453592.37,
                    'g': 453.592,
                    'kg': 0.453592,
                    'ml': 453.592,
                    'liter': 0.453592,
                    'gal': 0.119826,
                    'lbs': 1,
                    'oz': 16
                },
                'oz': {
                    'mg': 28349.5,
                    'g': 28.3495,
                    'kg': 0.0283495,
                    'ml': 28.3495,
                    'liter': 0.0283495,
                    'gal': 0.0078125,
                    'lbs': 0.0625,
                    'oz': 1
                }
            };

            if (fromUnit === toUnit || (fromUnit === 'dozan' && toUnit === 'dozan') || (fromUnit === 'piece' && toUnit ===
                    'piece')) {
                return quantity;
            } else if (conversionRates[fromUnit] && conversionRates[fromUnit][toUnit]) {
                return quantity * conversionRates[fromUnit][toUnit];
            } else {
                return quantity;
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
