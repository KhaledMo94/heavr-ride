<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (app()->getLocale() === 'ar') dir="rtl" @endif>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset(config('app.logo')) }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <title>{{ __('Forgot Password') }} - {{ config('app.name') }}</title>
<x-dashboard.styles />
<x-dashboard.scripts-header />

<style>
    .bg-gradient-quickdiscount {
        background: linear-gradient(135deg, #D71C4B 0%, #17B6AD 100%);
        background-attachment: fixed;
    }

    .logo {
        width: 100px;
        height: auto;
        margin-bottom: 1.5rem;
    }

    .btn-quickdiscount {
        background-color: #17B6AD;
        border: none;
    }

    .btn-quickdiscount:hover {
        background-color: #11897F;
    }

    @if (app()->getLocale() == 'ar')
    body * {
        font-family: 'Cairo', sans-serif !important;
    }
    @endif
</style>
</head>

<body class="bg-gradient-quickdiscount">
<div class="container v-center">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-5 col-md-6">
            <div class="my-5 border-0 shadow-lg card o-hidden">
                <div class="p-0 card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5 text-center">

                                <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" class="logo">

                                <h1 class="mb-4 text-gray-900 h4">{{ __('Forgot Password?') }}</h1>
                                <p class="text-muted mb-4">{{ __('No problem! Enter your email below and we’ll send you a reset link.') }}</p>

                                <!-- Session Status -->
                                @if (session('status'))
                                    <div class="alert alert-success mb-4" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('password.email') }}" class="user">
                                    @csrf

                                    <div class="form-group text-start">
                                        <label for="email" class="small text-gray-700">{{ __('Email Address') }}</label>
                                        <input id="email" type="email" class="form-control form-control-user"
                                            name="email" value="{{ old('email') }}" required autofocus
                                            placeholder="{{ __('Enter your email address') }}">
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="btn btn-quickdiscount btn-user btn-block">{{ __('Send Reset Link') }}</button>
                                </form>

                                <hr>

                                <a href="{{ route('login') }}" class="small text-primary">
                                    &larr; {{ __('Back to Login') }}
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-dashboard.scripts-footer />
</body>

</html>
