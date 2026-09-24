<!DOCTYPE html>
<html lang="en">

<head>
    @php($siteSettings = \App\Models\SiteSetting::current())
    <meta charset="utf-8" />
    @stack('user-title')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $siteSettings->site_favicon ? asset($siteSettings->site_favicon) : asset('backend/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('backend/assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
    <style>
        body.authentication-bg {
            min-height: 100vh;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .auth-shell {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            background: #ffffff;
        }
        .auth-card-wrap {
            padding: 40px 30px;
        }
        .auth-card-wrap .card {
            border: 0;
            box-shadow: none;
            background: transparent;
        }
    </style>
    @stack('extra-styles')

</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <div class="d-inline-block px-3 py-2 rounded-pill mb-3" style="background: rgba(var(--bs-primary-rgb), 0.1); color: var(--bs-primary); font-size: 0.85rem; font-weight: 600;">
                    <i class="bi bi-shield-lock-fill me-1"></i> Secure College Access
                </div>
                <h2 class="fw-bold" style="color: #1f2937;">{{ $siteSettings->site_name }}</h2>
            </div>
            <div class="col-12">
                <div class="auth-shell">
                    <div class="auth-card-wrap">
                        <div class="card">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Success - </strong> {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Success - </strong> {{ session('success') }}
                            </div>
                        @endif
                        @if (session('oops'))
                            <div class="alert alert-warning alert-dismissible bg-warning text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Success - </strong> {{ session('oops') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <p><strong>Opps Something went wrong</strong></p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center">
                            <a href="{{ route('home') }}">
                                <span><img src="{{ $siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png') }}" alt="{{ $siteSettings->site_name }}" height="68"></span>
                            </a>
                        </div>
                        @yield('backend-auth-content')
                            </div>
                        </div>
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <!-- bundle -->
    <script src="{{ asset('backend/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @stack('extra-scripts')

</body>

</html>
