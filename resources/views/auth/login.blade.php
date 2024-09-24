{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

    <style>
        /* Full body gradient background */
        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #001f3f, #f39c12);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: white;
        }

        /* Container for the image and form */
        .container {
            display: flex;
            width: 80%;
            max-width: 1000px;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Section for the image */
        .image-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 20px;
        }

        .image-section img {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        /* Section for the form */
        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(135deg, #f39c12, #001f3f);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Text styling for form titles */
        .login-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #ffffff;
        }

        .sub-title {
            font-size: 16px;
            font-weight: 300;
            margin-bottom: 30px;
            color: #ffdbb5;
            /* A subtle lighter shade */
        }

        /* Form input fields */
        .form-field {
            margin-bottom: 20px;
            position: relative;
        }

        .form-field input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            background: #f0f0f0;
            border: none;
            border-radius: 25px;
            color: #1b2a4e;
        }

        /* Icons inside the input fields */
        .form-field span.fas.fa-key,
        .form-field span.far.fa-envelope {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #1b2a4e;
        }

        /* Password visibility toggle */
        .form-field span.far.fa-eye,
        .form-field span.far.fa-eye-slash {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #1b2a4e;
        }

        /* Login button styling */
        button.btn {
            width: 100%;
            background: linear-gradient(135deg, #f39c12, #001f3f);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        /* Button hover effect */
        button.btn:hover {
            background-color: #e64e4e;
        }

        /* Link styling */
        .text-center a {
            color: #ffffff;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <div class="container">
        <!-- Image Section -->
        <div class="image-section">
            <img src="{{ asset('/LandingPage/image/Login.png') }}" alt="Sepatu Bersih">
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="text-center login-title">Login</div>
            <div class="text-center sub-title">Don't have an account yet?</div>
            <form class="p-3 mt-3" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-field d-flex align-items-center">
                    <span class="far fa-envelope"></span>
                    <input type="email" name="email" id="email" placeholder="Email" autocomplete="off">
                </div>
                <div class="form-field d-flex align-items-center position-relative">
                    <span class="fas fa-key"></span>
                    <input type="password" name="password" id="password" placeholder="Password">
                    <span class="far fa-eye position-absolute" id="togglePassword"></span>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="text-center fs-6">
                <a href="{{ route('landingPage') }}">Back to Landing Page?</a>
            </div>
        </div>
    </div>

    <!-- Custom Script -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>
