<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crust-House | Login Page</title>
    <link rel="stylesheet" href= "{{ asset('CSS/Owner/Login.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <form action="{{ route('storeRegistrationData') }} " method="POST" enctype="multipart/form-data">
            @csrf
            <div class="heading">
                <h1>Registration Panel</h1>
            </div>
            <hr>
            <div class="inputfields">

                <div class="namefield">
                    <input type="text" name="name" placeholder="Enter your full name" autocomplete="off" required>
                    <i class='bx bxs-user'></i>
                </div>

                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                
                <div class="emailfield">
                    <input type="email" name="email" placeholder="Email" autocomplete="off" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <div class="passwordfield">
                    <input type="password" id="password" name="password" placeholder="Password" autocomplete="off"
                    required>
                    <i class='bx bxs-lock-alt' onclick="showAndHidePswd()"></i>
                </div>

                <div class="passwordfield CnfrmPswdField">
                    <input type="password" id="cnfrmPswd" name="password_confirmation" placeholder="Confirm Password"
                    autocomplete="off" required>
                    <i class='bx bxs-lock-alt' onclick="showAndHideCnfrmPswd()"></i>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror

            </div>

            <div class="fgtpswd">
                <a href="{{ route('viewLoginPage') }}">Already have an account</a>
            </div>

            <div class="btn">
                <input type="submit" value="Register">
            </div>
        </form>

    </div>
    <script src="{{ asset('JavaScript/index.js') }}"></script>
</body>

</html>
