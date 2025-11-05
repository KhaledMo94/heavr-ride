<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (app()->getLocale() === 'ar') dir="rtl" @endif>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset(config('app.logo')) }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <title>{{ __('Admin Login') }} - {{ config('app.name') }}</title>
    <x-dashboard.styles />
    <x-dashboard.scripts-header />

@if (app()->getLocale() == 'ar')
    <link rel="stylesheet" href="{{ asset('backend/css/rtl.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:slnt,wght@-11..11,200..1000&display=swap" rel="stylesheet">
    <style>
        body * {
            font-family: 'Cairo', sans-serif !important;
        }
    </style>
@endif

<style>
    :root {
        --primary-color: #0C74D4;
        --primary-gradient: linear-gradient(135deg, #0C74D4, #2DAAE1);
    }

    body.bg-gradient-primary {
        background: var(--primary-gradient);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #095fa8;
        border-color: #095fa8;
    }

    .card {
        border-radius: 1rem;
    }

    .v-center {
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .logo-wrapper img {
        max-width: 140px;
        margin-bottom: 1rem;
    }

    .forgot-password {
        display: block;
        text-align: right;
        font-size: 0.9rem;
        margin-top: 10px;
        color: var(--primary-color);
        text-decoration: none;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }
</style>

</head>

<body class="bg-gradient-primary">
    <div class="container v-center">
        <div class="row justify-content-center w-100">
            <div class="col-xl-5 col-lg-5 col-md-6">
                <div class="my-5 border-0 shadow-lg card o-hidden">
                    <div class="card-body p-5 text-center">

                    <div class="logo-wrapper">
                        <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }} Logo">
                    </div>

                    <h1 class="mb-4 text-gray-900 h4">{{ __('Admin Login') }}</h1>

                    <form action="{{ route('login') }}" class="user" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <input id="email" type="email" class="form-control form-control-user"
                                name="email" value="{{ old('email') }}" autocomplete="email"
                                autofocus placeholder="{{ __('Email') }}">
                        </div>
                        <div class="form-group mb-3">
                            <input id="password" type="password" class="form-control form-control-user"
                                name="password" placeholder="{{ __('Password') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block mb-3">
                            {{ __('Login') }}
                        </button>
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            {{ __('Forgot Password?') }}
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<x-dashboard.scripts-footer />

</body>

</html>
