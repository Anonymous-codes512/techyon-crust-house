<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crust - House | Admin - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('CSS/Admin/admin.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>

<body>
    <div class="container">
        <nav>
            <div class="logo">
                <img src="{{ asset('Images/image 1.png') }}" alt="Logo Here">
            </div>

            <div class="menuList">
                <i class='bx bx-menu' id="menuIcon" onclick="toggleMenu()"></i>
                
                <div class="menu" id="menu">
                    <div class="menuItems active" id="menu1">
                        <i class='bx bxs-dashboard'></i>
                        <a href="{{ route('admindashboard') }}" style="text-decoration: none;">
                            <p class="link">Dashboard</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu2">
                        <i class='bx bxs-category'></i>
                        <a href="{{ route('viewCategoryPage') }}" style="text-decoration: none;">
                            <p class="link">Categories</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu2">
                        <i class='bx bx-package'></i>
                        <a href="{{ route('viewProductPage') }}" style="text-decoration: none;">
                            <p class="link">Products</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu3">
                        <i class='bx bxs-dock-bottom bx-rotate-180' ></i>
                        <a href="{{ route('viewDealPage') }}" style="text-decoration: none;">
                            <p class="link">Deals</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu4">
                        <i class='bx bxs-store'></i>
                        <a href="{{ route('viewStockPage') }}" style="text-decoration: none;">
                            <p class="link">Stock</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu5">
                        <i class='bx bxs-file-import'></i>
                        <a href="" style="text-decoration: none;">
                            <p class="link">Orders</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu8">
                        <i class='bx bxs-group'></i>
                        <a href="" style="text-decoration: none;">
                            <p class="link">My Staff</p>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="rgtpnl">
            <header id="header">
                <div class="searchbar">
                    <i class='bx bx-search'></i>
                    <input type="text" id="search" placeholder="Search">
                </div>

                <div class="profilepanel">
                    <div class="profile">
                        <div class="profilepic">
                            <img src="{{ asset('Images/Rectangle 3463281.png') }}" alt="Profile Picture">
                        </div>
                        <p class="profilename">{{ session('username') }}</p>
                    </div>

                    <a href="" class="logout">
                        <i class='bx bx-log-out-circle' title="logout"></i>
                    </a>

                    <div class="notification">
                        <i class='bx bx-bell' title="notifications"></i>
                    </div>

                    <div class="theme">
                        <i class='bx bx-moon' id="theme" title="theme change" onclick="toggleTheme()"></i>
                    </div>
                </div>
            </header>

            @yield('main')

        </div>
    </div>
    <script src="{{ asset('JavaScript/index.js') }}"></script>
</body>

</html>
