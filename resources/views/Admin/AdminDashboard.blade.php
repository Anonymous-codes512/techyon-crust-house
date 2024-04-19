@extends('Components.Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/admindashboard.css') }}">
@endpush

@section('main')
    <main id="dashboard">

        <div class="path">
            <p>Dashboard</p>
        </div>

        <div class="heading">
            <h3>Lahore Branch</h3>
        </div>

        <div class="cards">
            <a href="" class="category" id="category">
                <div class="icon">
                    <i class='bx bxs-category-alt'></i>
                </div>
                <div class="disc">
                    <p>Total Categories</p>
                    @if (session('totalCategories'))
                        <h3>{{ session('totalCategories') }}</h3>
                    @else
                        <h3>Nil</h3>
                    @endif
                </div>
            </a>

            <a href="" class="products" id="products">
                <div class="icon">
                    <i class='bx bx-package'></i>
                </div>
                <div class="disc">
                    <p>Total Products</p>
                    @if (session('totalProducts'))
                        <h3>{{ session('totalProducts') }}</h3>
                    @else
                        <h3>Nil</h3>
                    @endif
                </div>
            </a>

            <a href="" class="stock" id="stock">
                <div class="icon">
                    <i class='bx bxs-store'></i>
                </div>
                <div class="disc">
                    <p>Total Stock</p>
                    <h3>150</h3>
                </div>
            </a>

            <a href="" class="branchRevenue" id="branchRevenue">
                <div class="icon">
                    <i class='bx bx-dollar-circle'></i>
                </div>
                <div class="disc">
                    <p>Branch Revenue</p>
                    <h3>$120,530</h3>
                </div>
            </a>

        </div>

        <div class="graph">
            <div class="totalRevenueGraph">
                <div class="info">
                    <p class="ttle">Total Branch Revenue</p>
                </div>
                <canvas id="myChart">
                    <script>
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                    label: 'Total Revenue',
                                    data: [300, 100, 200, 300, 50, 250, 750, 150, 200, 67, 10, 400],
                                    backgroundColor: '#ffbb00',
                                    borderColor: '#ffbb00',
                                    borderWidth: 2
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

            <div class="monthlyRevenuegraph">
                <div class="info">
                    <p class="ttle">Monthly Branch Revenue</p>
                    <select class="filter" name="months">
                        <option value="January">January</option>
                        <option value="Feburary">Feburary</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="Augest">Augest</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>

                </div>
                <canvas id="barChart">
                    <script>
                        var ctx = document.getElementById('barChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Week1', 'Week2', 'Week3', 'Week4'],
                                datasets: [{
                                    label: 'Monthly Revenue',
                                    data: [300, 100, 500, 10],
                                    backgroundColor: '#ffbb00',
                                    borderColor: '#ffbb00',
                                    borderWidth: 2
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
            <h4>Branch Location</h4>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1400.1374189471333!2d74.3779472!3d31.4728824!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391906746a44dfef%3A0x49d8b59f64029da6!2sDHA%20Phase%203%2C%20Lahore%2C%20Punjab%2C%20Pakistan!5e1!3m2!1sen!2s!4v1712561394745!5m2!1sen!2s"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </main>

    <script>
        var resizeCanvas = function() {
            var graphContainers = document.querySelectorAll('.monthlyRevenuegraph, .totalRevenueGraph');
            graphContainers.forEach(function(container) {
                var canvas = container.querySelector('canvas');
                var width = container.offsetWidth;
                canvas.style.height = (width * 0.8) + 'px';
            });
        };
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
    </script>
@endsection
