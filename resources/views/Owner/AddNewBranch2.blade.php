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
        <input type="text" id="branchname" placeholder="Branch Name">
        <input type="number" id="branchcode" placeholder="Branch Code">
        <input type="text" id="branchlocation" placeholder="Branch Location">
        <input type="number" id="numberofitems" placeholder="Number of Items">
        <input type="number" id="numberofstaff" placeholder="Number of Staff">
        
        <div class="options">
            <div class="opt"><p class="opt-txt"> You want Rider </p><label class="switch"> <input type="checkbox"><span class="slider round"></span></label></div>
            <div class="opt"><p class="opt-txt"> You want Online Delivery </p> <label class="switch"> <input type="checkbox"><span class="slider round"></span></label></div>
            <p class="msg">Go to Payment Datails Settings</p>
            <div class="opt"><p class="opt-txt"> You went Dining Table </p> <label class="switch"> <input type="checkbox"><span class="slider round"></span></label></div>
        </div>
        
        <div class="btns">
            <button class="cancel"><a href=""  style="text-decoration: none;">Cancel</a></button>
            <button class="addnew" type="submit" ><a href="" style="text-decoration: none;">Update Now</a></button>
        </div>
    </form>
</main>
@endsection