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
                    <th>Member Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Staff as $staff)
                    <tr>
                        <td>{{ $staff->name }}</td>
                        <td>{{ $staff->email }}</td>
                        <td>{{ $staff->role }}</td>
                        <td>
                            <a onclick="editStaff({{ json_encode($staff) }})"><i class='bx bxs-edit-alt'></i></a>
                            <a href="{{ route('deleteStaff', $staff->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="overlay"></div>
        <form class="newstaff" id="newStaff" action="{{ route('storeRegistrationData') }} " method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Add New Staff Member</h3>

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
                <button id="cancel" onclick="closeAddStaff()">Cancel</button>
                <input type="submit" value="Add Now">
            </div>

        </form>


        <div id="editOverlay"></div>
        <form class="editstaff" id="editStaff" action="{{ route('updateStaff') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3>Edit Staff Member</h3>
            <input type="hidden" id="staffId" name="staffId">

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
                <label for="editrole">Member Role</label>
                <select name="role" id="editrole">
                    <option value="" selected disabled>Select the Role</option>
                    <option value="salesman">Sales Man</option>
                    <option value="chef">Chef</option>
                </select>
            </div>

            <div class="inputdivs">
                <label for="password">Password</label>
                <div class="passwordfield">
                    <input type="password" id="editpassword" name="password" required>
                    <i class='bx bxs-lock-alt' onclick="showAndHideEditPswd()"></i>
                </div>
            </div>

            <div class="inputdivs">
                <label for="cnfrmPswd">Confirm Password</label>
                <div class="passwordfield">
                    <input type="password" id="editcnfrmPswd" name="password_confirmation" required>
                    <i class='bx bxs-lock-alt' onclick="showAndHideEditCnfrmPswd()"></i>
                </div>
            </div>

            @error('password')
                <div onload="addStaff()" class="error-message">{{ $message }}</div>
            @enderror

            <div class="btns">
                <button type="button" id="Cancel" onclick="closeEditStaff()">Cancel</button>
                <input type="submit" value="Update Staff Data">
            </div>
        </form>
    </main>

    <script>
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
            let popup = document.getElementById('newStaff');

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
    </script>
@endsection
