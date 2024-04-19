@extends('Components.Owner')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Owner/DiningTable.css') }}">
@endpush

@section('main')
    <main id="Dining-Table">
        <div class="path">
            <p>Dashboard > Dining Table</p>
        </div>
        <div class="headings">
            <h3>Dining Table</h3>
            <button onclick="addTable()"> <i class='bx bx-plus'></i> Add New Table</button>
        </div>
        
        <section id="tables">
            <div class="row">
                <div class="card card1">
                    <div class="img-div"> <img src="{{ asset('Images/2s v table.png') }}" alt=""></div>
                    <p class="table-size">2 seat vip table</p>
                    <p class="table-avaliablity">Avaliable</p>
                </div>
                <div class="card card2">
                    <div class="img-div"> <img src="{{ asset('Images/2s table.png') }}" alt=""></div>
                    <p class="table-size">2 seat table</p>
                    <p class="table-avaliablity">Not Avaliable</p>
                </div>
                <div class="card card3">
                    <div class="img-div"> <img src="{{ asset('Images/3s table.png') }}" alt=""></div>
                    <p class="table-size">3 seat vip table</p>
                    <p class="table-avaliablity">Avaliable</p>
                </div>
                <div class="card card4">
                    <div class="img-div"> <img src="{{ asset('Images/3s tablev.png') }}" alt=""></div>
                    <p class="table-size">3 seat table</p>
                    <p class="table-avaliablity">Not Avaliable</p>
                </div>
            </div>
            <div class="row">
                <div class="card card1">
                    <div class="img-div"> <img src="{{ asset('Images/2s v table.png') }}" alt=""></div>
                    <p class="table-size">2 seat vip table</p>
                    <p class="table-avaliablity">Avaliable</p>
                </div>
                <div class="card card2">
                    <div class="img-div"> <img src="{{ asset('Images/2s table.png') }}" alt=""></div>
                    <p class="table-size">2 seat table</p>
                    <p class="table-avaliablity">Not Avaliable</p>
                </div>
                <div class="card card3">
                    <div class="img-div"> <img src="{{ asset('Images/3s table.png') }}" alt=""></div>
                    <p class="table-size">3 seat vip table</p>
                    <p class="table-avaliablity">Avaliable</p>
                </div>
                <div class="card card4">
                    <div class="img-div"> <img src="{{ asset('Images/3s tablev.png') }}" alt=""></div>
                    <p class="table-size">3 seat table</p>
                    <p class="table-avaliablity">Not Avaliable</p>
                </div>
            </div>
            <div class="row">
                <div class="card card1">
                    <div class="img-div"> <img src="{{ asset('Images/2s v table.png') }}" alt=""></div>
                    <p class="table-size">2 seat vip table</p>
                    <p class="table-avaliablity">Avaliable</p>
                </div>
                <div class="card card2">
                    <div class="img-div"> <img src="{{ asset('Images/2s table.png') }}" alt=""></div>
                    <p class="table-size">2 seat table</p>
                    <p class="table-avaliablity">Not Avaliable</p>
                </div>
                <div class="card card3">
                    <div class="img-div"> <img src="{{ asset('Images/3s table.png') }}" alt=""></div>
                    <p class="table-size">3 seat vip table</p>
                    <p class="table-avaliablity">Avaliable</p>
                </div>
                <div class="card card4">
                    <div class="img-div"> <img src="{{ asset('Images/3s tablev.png') }}" alt=""></div>
                    <p class="table-size">3 seat table</p>
                    <p class="table-avaliablity">Not Avaliable</p>
                </div>
            </div>
        </section>

        <div id="overlay"></div>

        <div class="newtable" id="newTable">
            <h3>Add New Rider</h3>
            <div class="inputdivs">
                <label for="name">Table Name</label>
                <input type="text" id="name" placeholder="Table Name" required>
            </div>
            <div class="inputdivs">
                <label for="contact">Number of seats</label>
                <input type="number" id="contact" placeholder="Number of seats" required>
            </div>
            <div class="inputdivs">
                <label for="email">Price</label>
                <input type="email" id="email" placeholder="Table Price" required>
            </div>
            <div class="inputdivs">
                <label for="cnic">How many number of tables</label>
                <input type="number" id="cnic" placeholder="Number of tables" required>
            </div>
            <div class="inputdivs">
                <label for="status">Select Status</label>
                <select name="status" id="status">
                    <option value="Avaliable">Avaliable</option>
                    <option value="Not Avaliable">Not Avaliable</option>
                </select>
            </div>
            <div class="btns">
                <button id="cancel" onclick="closeAddTable()">Cancel</button>
                <input type="submit" value="Add">
            </div>
        </div>

    </main>
@endsection