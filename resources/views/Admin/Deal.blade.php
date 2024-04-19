@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/deal.css') }}">
@endpush

@section('main')
    <main id="deal">
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
                    <th>Deal Price</th>
                    <th>Deal Status</th>
                    <th>Deal Products</th>
                    <th>Deal End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $imgUrl = null;
                @endphp

                @foreach ($dealsData as $deal)
                    <tr>
                        <td onclick="showDealInfo({{ json_encode($deal) }})"><img src={{ asset('Images/DealImages/' . $deal->dealImage) }} alt=" Deal Image"></td>
                        <td onclick="showDealInfo({{ json_encode($deal) }})">{{ $deal->dealTitle }}</td>
                        <td onclick="showDealInfo({{ json_encode($deal) }})">{{ $deal->dealPrice }}</td>
                        <td onclick="showDealInfo({{ json_encode($deal) }})">
                            <p class="status">{{ $deal->dealStatus }}</p>
                        </td>
                        <td onclick="showDealInfo({{ json_encode($deal) }})" class="ellipsis" style="max-width: 100px;">
                            {{ $deal->dealProducts }}</td>
                        <td onclick="showDealInfo({{ json_encode($deal) }})">{{ $deal->dealEndDate }}</td>
                        <td>
                            <a onclick= "editDeal({{ json_encode($deal) }})"><i class='bx bxs-edit-alt'></i></a>
                            <a href="{{ route('deleteDeal', $deal->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>

                        @if ($deal != null)
                            @php
                                $imgUrl =$deal->dealImage;
                            @endphp
                        @endif
                        
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
                <input type="text" id="dealTitle" name="dealTitle" placeholder="Deal title" required>
            </div>

            <div class="inputdivs">
                <input type="number" id="dealPrice" name="dealPrice" placeholder="Deal price" required>
            </div>

            <div class="inputdivs">
                <select name="dealStatus" id="dealStatus">
                    <option value="" selected disabled>Select Stauts</option>
                    <option value="active">Active</option>
                    <option value="not active">Not Active</option>
                </select>
            </div>

            <div class="inputdivs">
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

        <div id="editOverlay"></div>
        <form class="editdeal" id="editDeal" action="{{ route('updateDeal') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Edit Deal</h3>
            <hr>

            <input type="hidden" id="d-Id" name="dId">

            <div class="inputdivs">
                <label for="upload-update-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-update-file" name="dealImage" accept=".jpg,.jpeg,.png">
                    <p id="namefile"></p>
                </label>
            </div>

            <div class="inputdivs">
                <input type="text" id="deal-Title" name="dealTitle" required>
            </div>

            <div class="inputdivs">
                <input type="number" id="deal-Price" name="dealPrice"required>
            </div>

            <div class="inputdivs">
                <select name="dealStatus" id="deal-Status">
                    <option value="" selected disabled>Select Stauts</option>
                    <option value="active">Active</option>
                    <option value="not active">Not Active</option>
                </select>
            </div>

            <div class="inputdivs">
                <input type="date" id="deal-End-Date" name="dealEndDate" required>
            </div>

            <div class="btns">
                <button type="button" id="cancel" onclick="closeEditCatogry()">Cancel</button>
                <input type="submit" value="Update">
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
                <img id="dealInfoImage" src="{{ asset('Images/DealImages/' . $imgUrl) }}" alt="deal Image">
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

        const uploadFile = document.getElementById('upload-file');
        const filenameSpan = document.getElementById('filename');

        uploadFile.addEventListener('change', function(e) {
            const fileName = this.value.split('\\').pop();
            filenameSpan.textContent = fileName ? fileName : 'No file chosen';
        });

        function editDeal(Deal) {
            console.log(Deal)
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editDeal');
            overlay.style.display = 'block';
            popup.style.display = 'flex';
            document.getElementById('d-Id').value = Deal.id;
            document.getElementById('deal-Title').value = Deal.dealTitle;
            document.getElementById('deal-Price').value = Deal.dealPrice;
            document.getElementById('deal-Status').value = Deal.dealStatus;
            document.getElementById('deal-End-Date').value = Deal.dealEndDate;
        }

        function closeEditCatogry() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editDeal');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        const uploadUpdatedFile = document.getElementById('upload-update-file');
        const filenamSpan = document.getElementById('namefile');

        uploadUpdatedFile.addEventListener('change', function(e) {
            const fileNam = this.value.split('\\').pop();
            filenamSpan.textContent = fileNam ? fileNam : 'No file chosen';
        });

        function showDealInfo(deal) {

            let overlay = document.getElementById('dealInfoOverlay');
            let popup = document.getElementById('dealInfo');

            overlay.style.display = 'block';
            popup.style.display = 'flex';


            document.getElementById("dealInfoTitle").innerHTML = deal.dealTitle;
            document.getElementById("dealInfoPrice").innerHTML = deal.dealPrice + " Pkr";
            document.getElementById("dealInfoProducts").innerHTML = deal.dealProducts;
            document.getElementById("dealInfoStatus").innerHTML = deal.dealStatus;
            document.getElementById("dealInfoEndDate").innerHTML = deal.dealEndDate;

            // console.log(deal);
        }

        function hideDealInfo(deal) {
            let overlay = document.getElementById('dealInfoOverlay');
            let popup = document.getElementById('dealInfo');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }
    </script>
@endsection
