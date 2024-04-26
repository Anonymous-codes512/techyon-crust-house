@extends('Components.Owner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Owner/onlineorder.css') }}">
@endpush

@section('main')
    <main id="myStaff">
        <div class="path">
            <p>Dashboard > Online Orders</p>
        </div>
        <div class="headings">
            <h3>Online Orders</h3>
        </div> 
        
        <div class="processbtns">
            <a href="" style="text-decoration: none;"><button class="activebtn">In Process</button></a>
            <a href="" style="text-decoration: none;"><button class="inactivebtn">New Orders</button></a>
            <a href="" style="text-decoration: none;"><button class="inactivebtn">Completed</button></a>
        </div>

        <table>
            <thead>
                <tr>
                    <td>Order ID</td>
                    <td>Customer Name</td>
                    <td>Date</td>
                    <td>Time</td>
                    <td>Price</td>
                    <td>Payment Type</td>
                    <td>Status</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#78961</td>
                    <td>Brooklyn Simmons</td>
                    <td>May 25, 2023</td>
                    <td>11:30 AM</td>
                    <td>$150</td>
                    <td>online</td>
                    <td class="order-status">
                        <a href="">Reject</a>
                        <a href="">Accept</a>
                    </td>
                    <td>
                        <a href="" style="text-decoration: none">
                            <svg width="15" height="15" viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z">
                                </path>
                                <path
                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                </path>
                            </svg>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>#78961</td>
                    <td>Brooklyn Simmons</td>
                    <td>May 25, 2023</td>
                    <td>11:30 AM</td>
                    <td>$150</td>
                    <td>online</td>
                    <td class="order-status">
                        <a href="">Reject</a>
                        <a href="">Accept</a>
                    </td>
                    <td>
                        <a href="" style="text-decoration: none">
                            <svg width="15" height="15" viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z">
                                </path>
                                <path
                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                </path>
                            </svg>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>#78961</td>
                    <td>Brooklyn Simmons</td>
                    <td>May 25, 2023</td>
                    <td>11:30 AM</td>
                    <td>$150</td>
                    <td>online</td>
                    <td class="order-status">
                        <a href="">Reject</a>
                        <a href="">Accept</a>
                    </td>
                    <td>
                        <a href="" style="text-decoration: none">
                            <svg width="15" height="15" viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z">
                                </path>
                                <path
                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                </path>
                            </svg>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>#78961</td>
                    <td>Brooklyn Simmons</td>
                    <td>May 25, 2023</td>
                    <td>11:30 AM</td>
                    <td>$150</td>
                    <td>online</td>
                    <td class="order-status">
                        <a href="">Reject</a>
                        <a href="">Accept</a>
                    </td>
                    <td>
                        <a href="" style="text-decoration: none">
                            <svg width="15" height="15" viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z">
                                </path>
                                <path
                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                </path>
                            </svg>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>#78961</td>
                    <td>Brooklyn Simmons</td>
                    <td>May 25, 2023</td>
                    <td>11:30 AM</td>
                    <td>$150</td>
                    <td>online</td>
                    <td class="order-status">
                        <a href="">Reject</a>
                        <a href="">Accept</a>
                    </td>
                    <td>
                        <a href="" style="text-decoration: none">
                            <svg width="15" height="15" viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z">
                                </path>
                                <path
                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                </path>
                            </svg>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>#78961</td>
                    <td>Brooklyn Simmons</td>
                    <td>May 25, 2023</td>
                    <td>11:30 AM</td>
                    <td>$150</td>
                    <td>online</td>
                    <td class="order-status">
                        <a href="">Reject</a>
                        <a href="">Accept</a>
                    </td>
                    <td>
                        <a href="" style="text-decoration: none">
                            <svg width="15" height="15" viewBox="0 0 24 24" style="fill: rgb(25, 25, 25);transform: ;msFilter:;">
                                <path
                                    d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z">
                                </path>
                                <path
                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                </path>
                            </svg>
                        </a>
                    </td>
                </tr>
              
            </tbody>
        </table>
        {{-- {{ $data->links('vendor.pagination.bootstrap-4') }} --}}
    </main>
@endsection
