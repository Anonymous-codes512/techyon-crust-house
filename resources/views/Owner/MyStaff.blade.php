@extends('Components.Owner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Owner/myStaff.css') }}">
@endpush

@section('main')
    <main id="myStaff">
        @php
            $branches = $branches;
        @endphp
        <div class="path">
            <p>Dashboard > My Staff</p>
        </div>
        <div class="headings">
            <h3>My Staff</h3>
            <button onclick="addStaff()"> <i class='bx bx-plus'></i> Add New Rider</button>
        </div>

        <table id="EmpTable">
            <thead>
                <tr>
                    <td>Staff ID</td>
                    <td>Member Name</td>
                    <td>Contact Number</td>
                    <td>Email Address</td>
                    <td>Status</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1246</td>
                    <td>Robert Fox</td>
                    <td>(629)555-0129</td>
                    <td>robertfox@example.com</td>
                    <td class="status">Active</td>
                    <td>
                        <a href="" style="text-decoration: none"><svg width="15" height="15"
                                viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z">
                                </path>
                                <path
                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                </path>
                            </svg></a>
                        <a href="" style="text-decoration: none"><svg width="15" height="15"
                                viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z">
                                </path>
                            </svg></a>
                        <a href="" style="text-decoration: none"><svg width="15" height="15"
                                viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                </path>
                                <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                            </svg></a>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div id="overlay"></div>
        <form action="{{ route('storeRegistrationData')}}" class="addNewUser" id="addNewUser" method="post" enctype="multipart/form-data">
            @csrf
            <h3>Add New Staff Member</h3>

            <div class="inputdivs">
                <label for="upload-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-file" name="profile_picture" accept=".jpg,.jpeg,.png" required>
                    <p id="filename"></p>
                </label>
            </div>

            <div class="inputdivs">
                <label for="name">Enter Name</label>
                <input type="text" id="name" name="name" placeholder="Enter Name" required>
            </div>

            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <div class="inputdivs">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter Email Address" required>
            </div>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <div class="inputdivs">
                <label for="branch">Select Branch</label>
                <select name="branch" id="branch">
                    <option value="none" selected disabled>Select Branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branchName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="inputdivs">
                <label for="role">Select Status</label>
                <select name="role" id="role">
                    <option value="" selected disabled>Select the Role</option>
                    <option value="admin">Admin</option>
                    <option value="salesman">Sales Man</option>
                    <option value="chef">Chef</option>
                </select>
            </div>

            <div class="inputdivs">
                <label for="password">Password</label>
                <div class="passwordfield">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt' onclick="showAndHidePswd()"></i>
                </div>
            </div>

            <div class="inputdivs">
                <label for="cnfrmPswd">Confirm Password</label>
                <div class="passwordfield">
                    <input type="password" id="cnfrmPswd" name="password_confirmation" placeholder="Confirm Password"
                        required>
                    <i class='bx bxs-lock-alt' onclick="showAndHideCnfrmPswd()"></i>
                </div>
            </div>

            @error('password')
                <div onload="addStaff()" class="error-message">{{ $message }}</div>
            @enderror

            <div class="btns">
                <button id="cancel" type="button" onclick="closeAddStaff()">Cancel</button>
                <input type="submit" value="Add Now">
            </div>
        </form>

        <script>
            function addStaff() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('addNewUser');

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function editStaff(staff) {

            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStaff');

            document.getElementById('staffId').value = staff.id;
            document.getElementById('editname').value = staff.name;
            document.getElementById('editemail').value = staff.email;

            let roleDropdown = document.getElementById('editrole');
            for (let option of roleDropdown.options) {
                if (option.value === staff.role) {
                    option.selected = true;
                }
            }
            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeEditStaff() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStaff');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function closeAddStaff() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('addNewUser');

            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function showAndHidePswd() {
            let pswd = document.getElementById('epassword');
            if (pswd.type === 'password') {
                pswd.type = 'text';
            } else {
                pswd.type = 'password';
            }
        }

        function showAndHideEditPswd() {
            let pswd = document.getElementById('editpassword');
            if (pswd.type === 'password') {
                pswd.type = 'text';
            } else {
                pswd.type = 'password';
            }
        }

        function showAndHideEditCnfrmPswd() {
            let cnfrmPswd = document.getElementById('editcnfrmPswd');
            if (cnfrmPswd.type === 'password') {
                cnfrmPswd.type = 'text';
            } else {
                cnfrmPswd.type = 'password';
            }
        }

        function showAndHideCnfrmPswd() {
            let cnfrmPswd = document.getElementById('cnfrmPswd');
            if (cnfrmPswd.type === 'password') {
                cnfrmPswd.type = 'text';
            } else {
                cnfrmPswd.type = 'password';
            }
        }

        // const uploadUpdatedFile = document.getElementById('upload-update-file');
        // const filenamSpan = document.getElementById('namefile');
        // uploadUpdatedFile.addEventListener('change', function(e) {
        //     const fileNam = this.value.split('\\').pop();
        //     filenamSpan.textContent = fileNam ? fileNam : 'No file chosen';
        // });

        const uploadFile = document.getElementById('upload-file');
        const filenameSpan = document.getElementById('filename');
        uploadFile.addEventListener('change', function(e) {
            const fileName = this.value.split('\\').pop();
            filenameSpan.textContent = fileName ? fileName : 'No file chosen';
        });
        </script>
    </main>
@endsection
