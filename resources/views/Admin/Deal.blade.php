@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/deal.css') }}">
@endpush

@section('main')
    <main id="deal">

        @php
            $allDealProducts = $dealProducts;

        @endphp

        <div class="path">
            <p>Dashboard > Deals</p>
        </div>

        <div class="newDeal">
            <button onclick="addDeal()">Add New Deal</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deal Image</th>
                    <th>Deal Title</th>
                    <th>Deal Status</th>
                    {{-- <th>Deal Products</th> --}}
                    <th>Deal Price</th>
                    <th>Deal End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dealsData as $deal)
                    <tr>
                        <td onclick="showDealInfo({{ json_encode($deal) }}, {{ json_encode($allDealProducts) }} )">
                            <img src={{ asset('Images/DealImages/' . $deal->dealImage) }} alt=" Deal Image">
                        </td>
                        <td onclick="showDealInfo({{ json_encode($deal) }}, {{ json_encode($allDealProducts) }})">
                            {{ $deal->dealTitle }}</td>

                        <td onclick="showDealInfo({{ json_encode($deal) }}, {{ json_encode($allDealProducts) }})">
                            <p class="status">{{ $deal->dealStatus }}</p>
                        </td>

                        <td onclick="showDealInfo({{ json_encode($deal) }}, {{ json_encode($allDealProducts) }})">
                            {{ $deal->dealDiscountedPrice }}</td>

                        <td onclick="showDealInfo({{ json_encode($deal) }}, {{ json_encode($allDealProducts) }})">
                            {{ $deal->dealEndDate }}</td>

                        <td>
                            <a id="editButton"
                                onclick= "editDeal({{ json_encode($deal) }}, {{ json_encode($allDealProducts) }})"><i
                                    class='bx bxs-edit-alt'></i></a>
                            <a href= "{{ route('deleteDeal', $deal->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <div class="pagination"> {{ $categories->links() }} </div> --}}


        {{--  
            |---------------------------------------------------------------|
            |==================== Add new Deal Overlay =====================|
            |---------------------------------------------------------------|
        --}}

        <div id="overlay"></div>
        <form class="newdeal" id="newDeal" action="{{ route('createDeal') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <h3>Add New Deal</h3>
            <hr>

            <div class="inputdivs">
                <label for="upload-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-file" name="dealImage" accept=".jpg,.jpeg,.png" required>
                    <p id="filename"></p>
                </label>
            </div>

            <div class="inputdivs">
                <label for="dealTitle">Deal Title</label>
                <input type="text" id="dealTitle" name="dealTitle" placeholder="Deal title" required>
            </div>

            <div class="inputdivs">
                <label for="dealStatus">Deal Status</label>
                <select name="dealStatus" id="dealStatus">
                    <option value="" selected disabled>Select Stauts</option>
                    <option value="active">Active</option>
                    <option value="not active">Not Active</option>
                </select>
            </div>

            <div class="inputdivs">
                <label for="dealEndDate">Deal End Date</label>
                <input type="date" id="dealEndDate" name="dealEndDate" required>
            </div>

            <div class="btns">
                <button id="cancel" onclick="closeAddDeal()">Cancel</button>
                <input type="submit" value="Add Deal">
            </div>

        </form>

        {{--  
            |---------------------------------------------------------------|
            |====================== Edit Deal Overlay ======================|
            |---------------------------------------------------------------|
            --}}


        @if (session('editAfterDelete'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('editButton').click();
                });
            </script>
        @endif

        <div id="editOverlay"></div>
        <form class="editdeal" id="editDeal" action="{{ route('updateDeal') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div id="form-div">
                <h3>Edit Deal</h3>
                <hr>

                @php
                    $dealID = null;
                @endphp
                <input type="hidden" id="d-Id" name="dId">

                <div class="inputdivs">
                    <label for="upload-update-file" class="choose-file-btn">
                        <span>Choose File</span>
                        <input type="file" id="upload-update-file" name="dealImage" accept=".jpg,.jpeg,.png">
                        <p id="namefile"></p>
                    </label>
                </div>

                <div class="inputdivs">
                    <label for="deal-Title">Deal Title</label>
                    <input type="text" id="deal-Title" name="dealTitle" required>
                </div>

                <div class="inputdivs">
                    <label for="deal-price">Deal Discounted Price</label>
                    <input type="number" id="deal-price" name="dealprice" required>
                </div>
                
                <div class="inputdivs">
                    <label for="deal-Status">Deal Status</label>
                    <select name="dealStatus" id="deal-Status">
                        <option value="" selected disabled>Select Stauts</option>
                        <option value="active">Active</option>
                        <option value="not active">Not Active</option>
                    </select>
                </div>
                
                <div class="inputdivs">
                    <label for="deal-End-Date">Deal End Data</label>
                    <input type="date" id="deal-End-Date" name="dealEndDate" required>
                </div>
            </div>

            <hr id="line">

            <div id="product_details_tables">
                <table id="products_table">
                    <thead id="header">
                        <tr id="header-row">
                            <th class="header-row-headings">Products</th>
                            <th class="header-row-headings">Variation</th>
                            <th class="header-row-headings">Product Price</th>
                            <th class="header-row-headings">Quantity</th>
                            <th class="header-row-headings">Action</th>
                        </tr>
                    </thead>
                    <tbody id="body">
                    </tbody>
                </table>
            </div>

            <div class="btns">
                <p id="priceTag">Actual Price: <span id="price"></span></p>
                <button type="button" id="cancel" onclick="closeEditCatogry()">Cancel</button>
                <a id="add-product-link" style="text-decoration: none;"><input type="button" value="Add Product"></a>
                <input type="submit" value="Edit">
            </div>
        </form>

        {{--  
            |---------------------------------------------------------------|
            |================= On click deal Information ===================|
            |---------------------------------------------------------------|
        --}}

        <div id="dealInfoOverlay"></div>
        <div class="dealInfo" id="dealInfo">
            <h3>Deal Information</h3>
            <hr>

            <div class="imgdiv">
                <img id="dealInfoImage" alt="deal Image">
            </div>

            <div class="infodiv">
                <p id="dealInfoTitle"></p>
                <p id="dealInfoPrice"></p>
                <p id="dealInfoStatus"></p>
                <p id="dealInfoProducts"></p>
                <p id="dealInfoEndDate"></p>
            </div>

            <div class="btns" style="justify-content: center">
                <button id="cancel" onclick="hideDealInfo()" style="background-color: #ffbb00;">Close</button>
            </div>
        </div>

    </main>

    <script>
        function addDeal() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newDeal');

            overlay.style.display = 'block';
            popup.style.display = 'flex';


            let currentDate = new Date();
            let formattedDate = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + (
                '0' + currentDate.getDate()).slice(-2);
            document.getElementById('dealEndDate').min = formattedDate;
        }

        function closeAddDeal() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newDeal');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function editDeal(Deal, dealProducts) {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editDeal');
            overlay.style.display = 'block';
            popup.style.display = 'flex';

            document.getElementById('d-Id').value = Deal.id;
            document.getElementById('deal-Title').value = Deal.dealTitle;
            document.getElementById('deal-price').value = Deal.dealDiscountedPrice.replace(/\sPkr$/, "");
            document.getElementById('deal-Status').value = Deal.dealStatus;
            document.getElementById('deal-End-Date').value = Deal.dealEndDate;

            let totalProductPrice = 0;
            let dealId = Deal.id;
            let route = `{{ route('viewUpdateDealProductsPage', ':dealId') }}`;
            addroute = route.replace(':dealId', dealId);
            document.getElementById('add-product-link').setAttribute('href', addroute);

            let tbody = document.getElementById('body');
            tbody.innerHTML = '';
            dealProducts.forEach(product => {
                if (Deal.id === product.deal_id) {
                    
                    let newRow = document.createElement('tr');
                    newRow.setAttribute('id', 'body-row');

                    let productNameCell = document.createElement('td');
                    productNameCell.setAttribute('class', 'body-row-data');
                    productNameCell.textContent = product.productName;
                    newRow.appendChild(productNameCell);

                    let variationCell = document.createElement('td');
                    variationCell.setAttribute('class', 'body-row-data');
                    variationCell.textContent = product.productVariation;
                    newRow.appendChild(variationCell);

                    let priceCell = document.createElement('td');
                    priceCell.setAttribute('class', 'body-row-data');
                    priceCell.textContent = product.product_total_price;
                    newRow.appendChild(priceCell);

                    let productPrice = parseInt(product.product_total_price.replace(' Pkr', ''));
                    totalProductPrice += productPrice;

                    let quantityCell = document.createElement('td');
                    quantityCell.setAttribute('class', 'body-row-data');
                    quantityCell.textContent = product.product_quantity;
                    newRow.appendChild(quantityCell);

                    let actionCell = document.createElement('td');
                    actionCell.setAttribute('class', 'body-row-data');
                    let deleteLink = document.createElement('a');

                    let productId = product.handler_id;
                    let dealId = Deal.id;
                    let route = `{{ route('deleteDealProduct', [':productId', ':dealId']) }}`;
                    route = route.replace(':productId', productId).replace(':dealId', dealId);
                    deleteLink.setAttribute('href', route);

                    let trashIcon = document.createElement('i');
                    trashIcon.setAttribute('class', 'bx bxs-trash-alt');

                    deleteLink.appendChild(trashIcon);
                    actionCell.appendChild(deleteLink);
                    newRow.appendChild(actionCell);

                    tbody.appendChild(newRow);
                }
            });
            document.getElementById('price').textContent = totalProductPrice + " Pkr";
        }

        function closeEditCatogry() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editDeal');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function showDealInfo(deal, dealProducts) {
            let overlay = document.getElementById('dealInfoOverlay');
            let popup = document.getElementById('dealInfo');

            let productsInfo = '';
            dealProducts.forEach(product => {
                if (deal.id === product.deal_id) {
                    productsInfo += `${product.product_quantity} ${product.productName}, `;
                }
            });

            productsInfo = productsInfo.trim().replace(/,+$/, "");

            document.getElementById("dealInfoImage").src = `{{ asset('Images/DealImages/${deal.dealImage}') }}`;
            document.getElementById("dealInfoTitle").innerHTML = deal.dealTitle;
            document.getElementById("dealInfoPrice").innerHTML = deal.dealDiscountedPrice;
            document.getElementById("dealInfoProducts").innerHTML = productsInfo;
            document.getElementById("dealInfoStatus").innerHTML = deal.dealStatus;
            document.getElementById("dealInfoEndDate").innerHTML = deal.dealEndDate;

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function hideDealInfo() {
            let overlay = document.getElementById('dealInfoOverlay');
            let popup = document.getElementById('dealInfo');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        let texts = document.getElementsByClassName('status');
        Array.from(texts).forEach(text => {
            if (text.textContent.toLowerCase() === "active") {
                text.style.color = '#3FC28A';
                text.style.backgroundColor = '#3FC28A14';
            } else if (text.textContent.toLowerCase() === "not active") {
                text.style.color = '#F45B69';
                text.style.backgroundColor = '#F45B6914';
            }
        });

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
