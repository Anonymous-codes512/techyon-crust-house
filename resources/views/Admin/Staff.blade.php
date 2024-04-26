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
            <button onclick="popup()">Add New Staff</button>
        </div>

        <table id="EmpTable">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Contact Number</th>
                    <th>Email Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Robert Fox</td>
                    <td>(629)555-0129</td>
                    <td>robertfox@example.com</td>
                    <td class="status">Active</td>
                    <td>
                        <a href=""><i class='bx bxs-edit-alt'></i></a>
                        <a href=""><i class='bx bxs-trash-alt'></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="overlay"></div>
        <form class="newrider" id="newRider" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <h3>Add New Staff Member</h3>
            <div class="inputdivs">
                <label for="name">Enter Name</label>
                <input type="text" id="name" name="name" placeholder="Enter Name" required>
            </div>
            <div class="inputdivs">
                <label for="contact">Contact Number</label>
                <input type="number" id="contact" name="contactNumber" placeholder="Contact Number" required>
            </div>
            <div class="inputdivs">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="emailAddress" placeholder="Enter Email Address" required>
            </div>
            <div class="inputdivs">
                <label for="cnic">CNIC</label>
                <input type="number" id="cnic" name="cnicNumber" placeholder="Enter CNIC" required>
            </div>
            <div class="inputdivs">
                <label for="status">Select Status</label>
                <select name="status" id="status">
                    <option value="">Select the Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="btns">
                <button id="cancel" onclick="closePopup()">Cancel</button>
                <input type="submit" value="Add Now">
            </div>
        </form>
    </main>
@endsection
