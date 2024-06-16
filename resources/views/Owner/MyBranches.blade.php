@extends('Components.Owner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Owner/branches.css') }}">
@endpush

@section('main')
    <main id="mybranches">
        <div class="path">
            <p>Dashboard > Branches</p>
        </div>

        <div class="newBranch">
            <button type="button" onclick="addNewBranch()">Add New Branch</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Branch City</th>
                    <th>Branch Code</th>
                    <th>Branch Name</th>
                    <th>Branch Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($branchData as $branch)
                    <tr>
                        <td>{{ $branch->branchLocation }}</td>
                        <td>{{ $branch->branchCode }}</td>
                        <td>{{ $branch->branchName }}</td>
                        <td>{{ $branch->address }}</td>
                        <td>
                            <a onclick="editBranch({{ json_encode($branch) }})"><i class='bx bxs-edit-alt'></i></a>
                            <a href="{{ route('deleteBranch', $branch->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{--  
            |---------------------------------------------------------------|
            |================== Add new Branch Overlay =====================|
            |---------------------------------------------------------------|
        --}}

        <div id="addNewBranchOverlay"></div>
        <form id="addNewBranch" action="{{ route('storeNewBranchData') }}" method="Post" enctype="multipart/form-data">
            @csrf
            <h3>Add New Branch</h3>
            <hr>
            <div class="inputdivs">
                <label for="brancharea">Select City</label>
                <select name="branchArea" id="brancharea" required>
                    <option value="" selected>Select Location</option>
                    <option value="Lahore">Lahore</option>
                    <option value="Islamabad">Islamabad</option>
                    <option value="Sialkot">Sialkot</option>
                    <option value="Bakhar">Bakhar</option>
                    <option value="Multan">Multan</option>
                </select>

                @error('branchArea')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="branchname">Branch Name</label>
                <input type="text" name="branchname" id="branchname" placeholder="Branch Name" required>
                @error('branchname')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="branchcode">Branch Code</label>
                <input type="text" name="branchcode" id="branchcode" placeholder="Branch Code" required>
                @error('branchcode')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="address">Branch Address</label>
                <input type="text" name="address" id="address" placeholder="Address" required>
                @error('address')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="options">Select Additional Options</label>
                <div id="options" class="options">
                    <div class="opt">
                        <p class="opt-txt">You want Rider</p>
                        <label class="switch">
                            <input type="checkbox" name="riderOption">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="opt">
                        <p class="opt-txt">You want Online Delivery</p>
                        <label class="switch">
                            <input type="checkbox" name="onlineDeliveryOption">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="opt">
                        <p class="opt-txt">You went Dining Table</p>
                        <label class="switch">
                            <input type="checkbox" name="diningTableOption">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="btns">
                <button type="button" id="cancel" onclick="closeAddNewBranch()">Cancel</button>
                <input type="submit" value="Add Now">
            </div>
        </form>

        {{--  
            |---------------------------------------------------------------|
            |================== Edit new Branch Overlay ====================|
            |---------------------------------------------------------------|
        --}}

        <div id="editBranchOverlay"></div>
        <form id="editBranch" action="{{ route('updateBranches') }}" method="Post" enctype="multipart/form-data">
            @csrf
            <h3>Edit New Branch</h3>
            <hr>

            <input type="hidden" name="branch_id" id="branch_id">

            <div class="inputdivs">
                <label for="editbrancharea">Select City</label>
                <select name="branchArea" id="editbrancharea" required>
                    <option value="" selected>Select Location</option>
                    <option value="Lahore">Lahore</option>
                    <option value="Islamabad">Islamabad</option>
                    <option value="Sialkot">Sialkot</option>
                    <option value="Bakhar">Bakhar</option>
                    <option value="Multan">Multan</option>
                </select>

                @error('editbranchArea')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="editbranchname">Branch Name</label>
                <input type="text" name="branchname" id="editbranchname" placeholder="Branch Name" required>
                @error('editbranchname')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="editbranchcode">Branch Code</label>
                <input type="text" name="branchcode" id="editbranchcode" placeholder="Branch Code" required>
                @error('editbranchcode')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="editaddress">Branch Address</label>
                <input type="text" name="address" id="editaddress" placeholder="Address" required>
                @error('editaddress')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="inputdivs">
                <label for="editoptions">Select Additional Options</label>
                <div id="editoptions" class="options">
                    <div class="opt">
                        <p class="opt-txt">You want Rider</p>
                        <label class="switch">
                            <input type="checkbox" name="riderOption">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="opt">
                        <p class="opt-txt">You want Online Delivery</p>
                        <label class="switch">
                            <input type="checkbox" name="onlineDeliveryOption">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="opt">
                        <p class="opt-txt">You went Dining Table</p>
                        <label class="switch">
                            <input type="checkbox" name="diningTableOption">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="btns">
                <button type="button" id="editcancel" onclick="closeEditBranch()">Cancel</button>
                <input type="submit" value="Update Now">
            </div>
        </form>

    </main>
    <script>
        function addNewBranch() {
            const OVERLAY = document.getElementById('addNewBranchOverlay');
            const POPUP = document.getElementById('addNewBranch');
            OVERLAY.style.display = 'block';
            POPUP.style.display = 'flex';
        }

        function closeAddNewBranch() {
            const OVERLAY = document.getElementById('addNewBranchOverlay');
            const POPUP = document.getElementById('addNewBranch');
            OVERLAY.style.display = 'none';
            POPUP.style.display = 'none';
        }

        function editBranch(Branch) {
            const OVERLAY = document.getElementById('editBranchOverlay');
            const POPUP = document.getElementById('editBranch');

            console.log(Branch);

            document.getElementById('branch_id').value = Branch.id ;
            document.getElementById('editbrancharea').value = Branch.branchLocation ;
            document.getElementById('editbranchname').value = Branch.branchName ;
            document.getElementById('editbranchcode').value = Branch.branchCode ;
            document.getElementById('editaddress').value = Branch.address;

            OVERLAY.style.display = 'block';
            POPUP.style.display = 'flex';
        }

        function closeEditBranch() {
            const OVERLAY = document.getElementById('editBranchOverlay');
            const POPUP = document.getElementById('editBranch');
            OVERLAY.style.display = 'none';
            POPUP.style.display = 'none';
        }
    </script>
@endsection
