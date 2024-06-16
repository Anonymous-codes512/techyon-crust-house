<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crust-House | Login Page</title>
    <link rel="stylesheet" href= "{{ asset('CSS/Owner/Login.css') }}">
    {{-- <link rel="shortcut icon" href="{{ asset('Images/favicon.ico')}}" type="image/x-icon"> --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container">


        <form action="{{ route('login') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="heading">
                <h1>Login Panel</h1>
            </div>
            <hr>
            <p>Sign in to start your session</p>
            <div class="inputfields">
                <div class="emailfield">
                    <input type="email" name="email" placeholder="Email" autocomplete="off" required>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="passwordfield">
                    <input type="password" id="password" name="password" placeholder="Password" autocomplete="off"
                        required>
                    <i class='bx bxs-lock-alt' onclick="showAndHidePswd()"></i>
                </div>

                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- <div class="fgtpswd">
                <a href="">I forgot my Password</a>
                <a href="{{ route('viewRegisterPage') }}">Don't have an account</a>
            </div> --}}

            <div class="btn">
                <input type="submit" value="Login">
            </div>
        </form>

    </div>
    <script src="{{ asset('JavaScript/index.js') }}"></script>
</body>

</html>
