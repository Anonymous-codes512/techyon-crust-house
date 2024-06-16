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
                    <h3>1200</h3>
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
                    <p class="filter">Last 6 Months <i class='bx bx-chevron-down'></i> </p>
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

        <div class="map">
            <h4>Branches</h4>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14162684.943968251!2d58.35051958635448!3d29.930918993835288!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38db52d2f8fd751f%3A0x46b7a1f7e614925c!2sPakistan!5e0!3m2!1sen!2s!4v1711636812778!5m2!1sen!2s"
                style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </main>

    
@endsection
