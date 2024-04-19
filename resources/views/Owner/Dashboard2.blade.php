@extends('Components.Owner')

@section('main')
    <main id="dashboard">
        <div class="title">
            <h3>Dashboard</h3>
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
                                    data: [0, 100, 200, 300, 500, 1000],
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
                    <p class="filter">Last 6 Months  <i class='bx bx-chevron-down'></i> </p>
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
                                    data: [0, 100, 200, 300, 500, 1000],
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

        <div class="options">
            <div class="opt"> <input type="checkbox" class="option chk"> <p class="opt-txt"> You want Rider </p> </div>
            <div class="opt"> <input type="checkbox" class="option"> <p class="opt-txt"> You want Online Delivery </p> </div>
            <div class="opt"> <input type="checkbox" class="option"> <p class="opt-txt"> You went Dining Table </p> </div>
        </div>
 
    </main>

@endsection
 