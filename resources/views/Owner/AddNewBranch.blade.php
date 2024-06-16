@extends('Components.Owner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Owner/addNewBranch.css') }}">
@endpush

@section('main')
 
<main id="dashboard">
    <div class="path"> 
        <p>Dashboard > Add New Branch</p>
    </div>

    <form action="{{ route('storeNewBranchData') }}" method="POST" enctype="multipart/form-data">
        @csrf
    
        <h3 class="heading">Add New Branch</h3>

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
    
        <input type="text" name="branchname" id="branchname" placeholder="Branch Name" required>
        @error('branchname')
            <span class="error-message">{{ $message }}</span>
        @enderror
    
        <input type="text" name="branchcode" id="branchcode" placeholder="Branch Code" required>
        @error('branchcode')
            <span class="error-message">{{ $message }}</span>
        @enderror
    
        <input type="text" name="address" id="address" placeholder="Address" required>
        @error('address')
            <span class="error-message">{{ $message }}</span>
        @enderror
    
        <div class="options">
            <div class="opt"><p class="opt-txt"> You want Rider </p><label class="switch"> <input type="checkbox" name="riderOption"><span class="slider round"></span></label></div>
            <div class="opt"><p class="opt-txt"> You want Online Delivery </p> <label class="switch"> <input type="checkbox" name="onlineDeliveryOption"><span class="slider round"></span></label></div>
            <div class="opt"><p class="opt-txt"> You went Dining Table </p> <label class="switch"> <input type="checkbox" name="diningTableOption"><span class="slider round"></span></label></div>
        </div>
        
        <div class="btns">
            <button class="cancel"><a href="{{ route('dashboard') }}"  style="text-decoration: none;">Cancel</a></button>
            <input type="submit" value="Add Now" class="addnew">
        </div>
    </form>
</main>
@endsection