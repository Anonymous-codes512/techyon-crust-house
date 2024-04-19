@extends('Components.Owner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Owner/BranchesDashboard.css') }}">
@endpush

@section('main')
    <main id="dashboard">
        <div class="title">
            <h3><Select id="city">
                <option value="" selected disabled>Select Branch Location</option>
                @foreach ($branchData as $branch)
                    <option value="{{ $branch->branchLocation }}">{{ $branch->branchLocation}} Dashboard</option>
                @endforeach
            </Select></h3>
        </div>
        <div class="stat">

            <div class="totalRevenue" id="totalrevenue">
                <div class="icon">
                    <i class='bx bx-dollar-circle'></i>
                </div>
                <div class="disc">
                    <p>Total Revenue</p>
                    <h3>$120,800</h3>
                </div>
            </div>

            <div class="totalBranch" id="totalmenu">
                <div class="icon">
                    <i class='bx bx-package'></i>
                </div>
                <div class="disc">
                    <p>Total Branch</p>
                    @if (session('totalBranches'))
                        <h3>{{ session('totalBranches') }}</h3>
                    @else
                        <h3>Nil</h3>                        
                    @endif

                </div>
            </div>

            <div class="totalMenu" id="totalmenu">
                <div class="icon">
                    <i class='bx bxs-dish'></i>
                </div>
                <div class="disc">
                    <p>Total Menu</p>
                    <h3>150</h3>
                </div>
            </div>
     
            <div class="totalStaff" id="totalstaff">
                <div class="icon">
                    <i class='bx bxs-group'></i>
                </div>
                <div class="disc">
                    <p>Total Staff</p>
                    <h3>120</h3>
                </div>
            </div>

        </div>

        <div class="graph">
            <div class="revenueGraph">
                <div class="info">
                    <p class="ttle">Total Revenue</p>
                    <p class="filter">Filter <i class='bx bx-filter-alt'></i> </p>
                </div>
                <canvas id="myChart">
                    <script>
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                    data: [300, 100, 200, 300, 50, 250],
                                    backgroundColor: 'rgba(0, 0, 0, 0)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </canvas>
            </div>

            <div class="orderGraph">
                <div class="info">
                    <p class="ttle">Total Orders</p>
                    <select class="filter" name="moths" >
                        <option value="3 month">Last 3 Months</option>
                        <option value="6 month">Last 6 Months</option>
                        <option value="12 month">Last 12 Months</option>
                    </select>

                </div>
                <canvas id="barChart">
                    <script>
                        var ctx = document.getElementById('barChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                                datasets: [{
                                    label: 'Total Revenue',
                                    data: [300, 50, 200, 300, 500, 1000],
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>

                </canvas>
            </div>
        </div>

        <div class="deliveries">

            <div class="rider">
                <div class="icon">
                    <i class='bx bxs-group'></i>
                </div>
                <div class="disc">
                    <p>Rider</p>
                    <h3>5</h3>
                </div>
            </div>

            <div class="dining">
                <div class="icon">
                    <i class='bx bxs-group'></i>
                </div>
                <div class="disc">
                    <p>Dining Table</p>
                    <h3>8</h3>
                </div>
            </div>

            <div class="online">
                <div class="icon">
                    <i class='bx bxs-group'></i>
                </div>
                <div class="disc">
                    <p>Online Delivery</p>
                    <h3>Nill</h3>
                </div>
            </div>
        </div>
 
    </main>



@endsection
