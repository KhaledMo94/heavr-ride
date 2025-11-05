<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@php
    $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
@endphp

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset(config('app.logo')) }}">
    <title>{{ __('Admin Panel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @livewireStyles
    @if (app()->getLocale() == 'ar')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:slnt,wght@-11..11,200..1000&display=swap"
            rel="stylesheet">
    @endif

    <x-dashboard.styles />
    <style>
        .select2-search__field {
            margin-top: 0 !important;
            height: 25px !important;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.3em;
            background-color: #dee2e6;
            border: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: #198754;
        }

        .form-switch .form-check-input:focus {
            box-shadow: none;
        }
    </style>
    <x-dashboard.scripts-header />

</head>

<body id="page-top" @if (app()->getLocale() == 'ar') dir="rtl" @endif style="overflow-x: hidden;">

    <div id="wrapper">

        <x-dashboard.side-bar />

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="mb-4 bg-white shadow navbar navbar-expand navbar-light topbar static-top">
                    <button id="sidebarToggleTop" class="mr-3 btn btn-link d-md-none rounded-circle">
                        <i class="fa fa-bars"></i>
                    </button>
                    <form action="{{ route('admins.language.switch') }}" method="POST">
                        @csrf
                        <select name="locale" onchange="this.form.submit()" class="form-control">
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                            <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                        </select>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="ml-auto navbar-nav">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 text-gray-600 d-none d-lg-inline">
                                    {{ Auth::user()->name ?? __('Admin') }}
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('storage/' . Auth::user()->image) }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in"
                                aria-labelledby="userDropdown">

                                <a class="dropdown-item" href="{{ route('admins.profile.edit') }}">
                                    <i class="mr-2 text-gray-400 fas fa-user fa-sm fa-fw"></i> {{ __('Edit Profile') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admins.password.edit') }}">
                                    <i class="mr-2 text-gray-400 fas fa-user fa-sm fa-fw"></i>
                                    {{ __('Change Password') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="mr-2 text-gray-400 fas fa-sign-out-alt fa-sm fa-fw"></i>
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{ $slot }}

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="rounded scroll-to-top" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <x-dashboard.scripts-footer />
    <!-- <script src="{{ asset('build/assets/app-eMHK6VFw.js') }}"></script> -->

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            console.log('%c[Livewire] Initialized', 'color: green; font-weight: bold;');

            const initBootstrapToggle = () => {
                setTimeout(() => {
                    console.log('%c[Bootstrap Toggle] Initializing...', 'color: orange;');
                    const $toggles = $('input[data-toggle="toggle"]');

                    if ($toggles.length === 0) {
                        console.log('%c[Bootstrap Toggle] No toggles found.', 'color: gray;');
                        return;
                    }

                    try {
                        $toggles.bootstrapToggle('destroy');
                    } catch (e) {
                        console.warn('[Bootstrap Toggle] Destroy skipped.');
                    }

                    $toggles.bootstrapToggle();
                    console.log(`%c[Bootstrap Toggle] Reinitialized ${$toggles.length} toggle(s).`,
                        'color: orange; font-weight: bold;');
                }, 1); // delay helps ensure Livewire DOM patch completes
            };

            const initYourJS = () => {
                console.log('%c[JS] initYourJS() called', 'color: dodgerblue;');
            };

            // Initial load
            initYourJS();
            initBootstrapToggle();

            // After Livewire render (commit)
            Livewire.hook('commit', ({
                respond
            }) => {
                respond(() => {
                    console.log('%c[Livewire] DOM updated → reinitializing...', 'color: teal;');
                    initYourJS();
                    initBootstrapToggle();
                });
            });

            // Listen for custom event
            Livewire.on('jsInit', () => {
                console.log('%c[Event] jsInit received → reinitializing toggles...', 'color: red;');
                initBootstrapToggle();
            });
        });
    </script>


</body>

</html>
