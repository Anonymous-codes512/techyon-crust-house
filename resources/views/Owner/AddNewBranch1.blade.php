@extends('Components.Owner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Owner/addNewBranch.css') }}">
@endpush

@section('main')
 
<main id="dashboard">
    <div class="path">
        <p>Dashboard > Add New Branch</p>
    </div>
    <form action="">
        <h3 class="heading">Add New Branch</h3>
        <select name="branchArea" id="brancharea">
            <option value="Lahore Dashboard" selected>Lahore Dashboard</option>
            <option value="Islamabad Dashboard">Islamabad Dashboard</option>
            <option value="Sialkot Dashboard">Sialkot Dashboard</option>
            <option value="Bakhar Dashboard">Bakhar Dashboard</option>
            <option value="Multan Dashboard">Multan Dashboard</option>
        </select>
        <input type="text" id="branchname" placeholder="Branch Name">
        <input type="number" id="branchcode" placeholder="Branch Code">
        <input type="number" id="streatnumber" placeholder="Streat Number">
        <input type="number" id="numberofitems" placeholder="Number of Items">
        <input type="number" id="numberofstaff" placeholder="Number of Staff">
        <input type="number" id="numberoftables" placeholder="Number of Tables">
        
        <div class="options">
            <div class="opt"><p class="opt-txt"> You want Online Delivery </p> <label class="switch"> <input type="checkbox"><span class="slider round"></span></label></div>
        </div>
        
        <input type="number" id="numberofriders" placeholder="Number of Raider">
        
        <div class="btns">
            <button class="cancel"><a href=""  style="text-decoration: none;">Cancel</a></button>
            <button class="addnew" type="submit" ><a href="" style="text-decoration: none;">Add Now</a></button>
        </div>
    </form>
</main>
@endsection