@extends('Components.Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/staff.css') }}">
@endpush

@section('main')
    <main id="myStaff">
        <div class="path">
            <p>Dashboard > My Staff</p>
        </div>
        <div class="headings">
            <h3>My Staff</h3>
            <button onclick="addStaff()">Add New Staff</button>
        </div>

        <table id="EmpTable">
            <thead>
                <tr>
                    <th>Profile Picture</th>
                    <th>Member Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Staff as $staff)
                    @if ($staff->role !== 'admin')
                        <tr>
                            <td><img src={{ asset('Images/UsersImages/' . $staff->profile_picture) }} alt="Image"></td>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>{{ $staff->role }}</td>
                            <td>
                                <a onclick="editStaff({{ json_encode($staff) }})"><i class='bx bxs-edit-alt'></i></a>
                                <a href="{{ route('deleteStaff', $staff->id) }}"><i class='bx bxs-trash-alt'></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div id="overlay"></div>
        <form class="newstaff" id="newStaff" action="{{ route('storeRegistrationData') }}" method="POST"
            enctype="multipart/form-data" onsubmit="return checkFormSubmission()">
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
                        <option value="{{ $branch->id }}">{{ $branch->branchName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="inputdivs">
                <label for="role">Select Status</label>
                <select name="role" id="role">
                    <option value="" selected disabled>Select the Role</option>
                    <option value="salesman">Sales Man</option>
                    <option value="chef">Chef</option>
                </select>
            </div>

            <div class="inputdivs">
                <label for="password">Password</label>
                <div class="passwordfield">
                    <input type="password" id="password" name="password" placeholder="Password" required
                        oninput="validatePassword()">
                    <i class='bx bxs-lock-alt' onclick="showAndHidePswd()"></i>
                </div>
            </div>

            <div class="inputdivs">
                <label for="cnfrmPswd">Confirm Password</label>
                <div class="passwordfield">
                    <input type="password" id="cnfrmPswd" name="password_confirmation" placeholder="Confirm Password"
                        required oninput="validatePassword()">
                    <i class='bx bxs-lock-alt' onclick="showAndHideCnfrmPswd()"></i>
                </div>
            </div>

            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div id="message" class="error"></div>
            <div class="btns">
                <button id="cancel" type="button" onclick="closeAddStaff()">Cancel</button>
                <input type="submit" value="Add Now">
            </div>
        </form>

        <div id="editOverlay"></div>
        <form class="editstaff" id="editStaff" action="{{ route('updateStaff') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Edit Staff Member</h3>
            <input type="hidden" id="staffId" name="staffId">

            <div class="inputdivs">
                <label for="upload-update-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-update-file" name="updated_profile_picture" accept=".jpg,.jpeg,.png">
                    <p id="namefile"></p>
                </label>
            </div>

            <div class="inputdivs">
                <label for="editname">Member Name</label>
                <input type="text" id="editname" name="name" required>
            </div>

            <div class="inputdivs">
                <label for="editemail">Member Email Address</label>
                <input type="email" id="editemail" name="email" required>
            </div>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <div class="inputdivs">
                <label for="editbranch">Select Branch</label>
                <select name="branch" id="editbranch">
                    <option value="none" selected disabled>Select Branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branchName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="inputdivs">
                <label for="editrole">Member Role</label>
                <select name="role" id="editrole">
                    <option value="" selected disabled>Select the Role</option>
                    <option value="salesman">Sales Man</option>
                    <option value="chef">Chef</option>
                </select>
            </div>

            <div class="inputdivs">
                <label for="editpassword">Password</label>
                <div class="passwordfield">
                    <input type="password" id="editpassword" name="password" oninput="validateEditPassword()">
                    <i class='bx bxs-lock-alt' onclick="showAndHideEditPswd()"></i>
                </div>
            </div>

            <div class="inputdivs">
                <label for="editcnfrmPswd">Confirm Password</label>
                <div class="passwordfield">
                    <input type="password" id="editcnfrmPswd" name="password_confirmation"
                        oninput="validateEditPassword()">
                    <i class='bx bxs-lock-alt' onclick="showAndHideEditCnfrmPswd()"></i>
                </div>
            </div>

            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div id="editMessage" class="error"></div>
            <div class="btns">
                <button type="button" id="Cancel" onclick="closeEditStaff()">Cancel</button>
                <input type="submit" value="Update Staff Data">
            </div>
        </form>
    </main>

    <script>
        function validatePassword() {
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('cnfrmPswd').value;
            let message = document.getElementById('message');

            if (password.length < 8) {
                message.textContent = "Password must be at least 8 characters long!";
                message.className = "error";
            } else if (password !== confirmPassword) {
                message.textContent = "Passwords do not match!";
                message.className = "error";
            } else {
                message.textContent = "Passwords match!";
                message.className = "success";
            }
        }

        function validateEditPassword() {
            let password = document.getElementById('editpassword').value;
            let confirmPassword = document.getElementById('editcnfrmPswd').value;
            let message = document.getElementById('editMessage');

            if (password.length < 8) {
                message.textContent = "Password must be at least 8 characters long!";
                message.className = "error";
            } else if (password !== confirmPassword) {
                message.textContent = "Passwords do not match!";
                message.className = "error";
            } else {
                message.textContent = "Passwords match!";
                message.className = "success";
            }
        }

        function checkFormSubmission() {
            validatePassword();
            let message = document.getElementById('message');

            if (message.className === "success") {
                return true;
            } else {
                return false;
            }
        }

        function addStaff() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newStaff');

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function editStaff(staff) {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStaff');

            document.getElementById('staffId').value = staff.id;
            document.getElementById('editname').value = staff.name;
            document.getElementById('editemail').value = staff.email;

            let branchDropdown = document.getElementById('editbranch');
            for (let option of branchDropdown.options) {
                if (option.value == staff.branch_id) {
                    option.selected = true;
                    break;
                }
            }

            let roleDropdown = document.getElementById('editrole');
            for (let option of roleDropdown.options) {
                if (option.value == staff.role) {
                    option.selected = true;
                    break;
                }
            }

            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeEditStaff() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('editStaff');
            popup.reset();
            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function closeAddStaff() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newStaff');
            popup.reset();
            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function showAndHidePswd() {
            let pswd = document.getElementById('password');
            if (pswd.type === 'password') {
                pswd.type = 'text';
            } else {
                pswd.type = 'password';
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

        const uploadUpdatedFile = document.getElementById('upload-update-file');
        const filenameSpan = document.getElementById('namefile');
        uploadUpdatedFile.addEventListener('change', function(e) {
            const fileName = this.value.split('\\').pop();
            filenameSpan.textContent = fileName ? fileName : 'No file chosen';
        });

        const uploadFile = document.getElementById('upload-file');
        const filenameSpanNew = document.getElementById('filename');
        uploadFile.addEventListener('change', function(e) {
            const fileName = this.value.split('\\').pop();
            filenameSpanNew.textContent = fileName ? fileName : 'No file chosen';
        });
    </script>
@endsection
