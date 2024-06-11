<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <a href="{{ route('admindashboard') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu1')">
                            <p class="link">Dashboard</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu2">
                        <i class='bx bxs-category'></i>
                        <a href="{{ route('viewCategoryPage') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu2')">
                            <p class="link">Categories</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu3">
                        <i class='bx bx-package'></i>
                        <a href="{{ route('viewProductPage') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu3')">
                            <p class="link">Products</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu4">
                        <i class='bx bxs-dock-bottom bx-rotate-180'></i>
                        <a href="{{ route('viewDealPage') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu4')">
                            <p class="link">Deals</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu5">
                        <i class='bx bxs-store'></i>
                        <a href="{{ route('viewStockPage') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu5')">
                            <p class="link">Stock</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu6">
                        <i class='bx bx-cookie'></i>
                        <a href="{{ route('viewRecipePage') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu6')">
                            <p class="link">Recipe</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu7">
                        <i class='bx bxs-file-import'></i>
                        <a href="{{ route('viewOrdersPage') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu7')">
                            <p class="link">Orders</p>
                        </a>
                    </div>
                    <div class="menuItems" id="menu8">
                        <i class='bx bxs-group'></i>
                        <a href="{{ route('viewStaffPage') }}" style="text-decoration: none;"
                            onclick="setActiveMenu('menu8')">
                            <p class="link">My Staff</p>
                        </a>
                    </div>
                </div>
            </div>
            <script>
                function setActiveMenu(menuId) {
                    document.cookie = "activeMenu=" + menuId + "; path=/";
                    document.querySelectorAll('.menuItems').forEach(item => {
                        item.classList.remove('active');
                    });
                    document.getElementById(menuId).classList.add('active');
                }

                document.addEventListener('DOMContentLoaded', (event) => {
                    const activeMenu = getActiveMenu();
                    if (activeMenu) {
                        document.querySelectorAll('.menuItems').forEach(item => {
                            item.classList.remove('active');
                        });
                        document.getElementById(activeMenu).classList.add('active');
                    }
                });

                function getActiveMenu() {
                    const value = `; ${document.cookie}`;
                    const parts = value.split(`; activeMenu=`);
                    if (parts.length === 2) return parts.pop().split(';').shift();
                }
            </script>


        </nav>

        <div class="rgtpnl">
            <header id="header">
                <div class="searchbar">
                    <i class='bx bx-search'></i>
                    <input type="text" id="search" value="" placeholder="Search">
                </div>

                <div class="profilepanel">
                    <div class="profile">
                        <div class="profilepic">
                            @if (session('profile_pic'))
                                <img src="{{ asset('Images/UsersImages/' . session('profile_pic')) }}"
                                    alt="Profile Picture">
                            @else
                                <img src="{{ asset('Images/Rectangle 3463281.png') }}" alt="Profile Picture">
                            @endif
                        </div>


                        @if (session('username'))
                            <p class="profilename">{{ session('username') }}</p>
                        @endif

                    </div>

                    @php
                        $notifications = session('Notifications');
                    @endphp

                    @if (!$notifications || $notifications->isEmpty())
                        <div class="notification">
                            <i class='bx bxs-bell' title="notifications" onclick="toggleNotification()"></i>
                            <div id="notificationBox" class="notificationBox">
                                <p id="heading">Notifications</p>
                                <div class="message">
                                    <p>No new notifications</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="notification">
                            <i class='bx bxs-bell-ring bx-tada' style="color: #ffbb00;" title="notifications"
                                onclick="toggleNotification()"></i>
                            <div id="notificationBox" class="notificationBox">
                                <p id="heading">Notifications</p>

                                @foreach ($notifications as $notification)
                                    <div class="message">
                                        <a href="{{ route('redirectNotification', $notification->id) }}"
                                            style="text-decoration:none; color:black;">
                                            <p>{{ $notification->message }}</p>
                                        </a>
                                        <div class="buttons">
                                            <a href="{{ route('readNotification', $notification->id) }}"><i
                                                    class='bx bxs-book-reader' title="Read"></i></a>
                                            <a href="{{ route('deleteNotification', $notification->id) }}"><i
                                                    class='bx bxs-trash' title="Delete"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('logout') }}" class="logout" onclick="clearCookies()">
                        <i class='bx bx-log-out-circle' title="logout"></i>
                    </a>

                </div>
            </header>

            <script>
                function clearCookies() {
                    document.cookie = 'activeMenu=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                    document.cookie = 'laravel_session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                    window.location.href = "{{ route('logout') }}";
                }
            </script>

            @yield('main')

        </div>
    </div>
    <script src="{{ asset('JavaScript/index.js') }}"></script>
    <script>
        function toggleNotification() {
            const notificationBox = document.getElementById('notificationBox');
            if (notificationBox.style.display === "flex") {
                notificationBox.style.display = "none";
            } else {
                notificationBox.style.display = "flex";
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
