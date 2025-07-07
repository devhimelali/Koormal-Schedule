<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-sidebar="dark"
    data-sidebar-size="lg" data-preloader="disable" data-theme="default" data-topbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ env('APP_DESCRIPTION') }}" name="description">
    <meta content="{{ env('APP_NAME') }}" name="author">
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Fonts css load -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link id="fontsLink"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .custom-tooltip .tooltip-inner {
            max-width: 300px;
            /* Adjust this as needed */
            white-space: pre-wrap;
            /* Keeps line breaks */
        }
    </style>
    @yield('vendor-style')
    @yield('page-style')
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('redirect') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/4emus.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/4emus.png') }}" alt="" height="22">
                    </span>
                </a>
                <a href="{{ route('redirect') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/4emus.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/4emus.png') }}" alt="" height="22">
                    </span>
                </a>
                <button type="button" class="p-0 btn btn-sm fs-3xl header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                @hasrole('admin')
                    @include('admin.partials.sidebar')
                @endhasrole
                @hasrole('user')
                    @include('user.partials.sidebar')
                @endhasrole
                @hasrole('technician')
                    @include('technician.partials.sidebar')
                @endhasrole
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
        <header id="page-topbar">
            <div class="layout-width">
                @include('layouts.partials.topbar')
            </div>
        </header>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div><!--end row-->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        @include('layouts.partials.footer')
    </div>
    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->
    <!--start back-to-top-->
    <button class="btn btn-dark btn-icon" id="back-to-top">
        <i class="bi bi-caret-up fs-3xl"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div id="loader"
        style="display: none; position: fixed; top: 50%; left: 50%;
    transform: translate(-50%, -50%); z-index: 1051; background-color: rgba(208,208,208,0.3); width: 100% ; height: 100%;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        $('body').on('click', '.scheduleMenuBtn', function() {
            Swal.fire({
                title: 'Select Asset Type',
                icon: 'question',
                html: `
                 <p>Nothing you choose here will affect the schedule.</p>
                <button class="swal2-confirm btn-option btn btn-sm btn-primary" data-value="light_vehicle">Light Vehicles</button>
                <button class="swal2-confirm btn-option btn btn-sm btn-secondary" data-value="lighting_tower">Lighting Towers</button>
                <button class="swal2-confirm btn-option btn btn-sm btn-success" data-value="truck">Truck</button>
                <button class="swal2-confirm btn-option btn btn-sm btn-info" data-value="forklift">Forklift / Manitou</button>
                <button class="swal2-confirm btn-option btn btn-sm btn-warning" data-value="pumps">Pumps</button>
              `,
                showConfirmButton: false,
                didOpen: () => {
                    document.querySelectorAll('.btn-option').forEach(button => {
                        button.addEventListener('click', () => {
                            const selected = button.getAttribute('data-value');
                            Swal.close();
                            if (selected === 'light_vehicle') {
                                window.location.href =
                                    "{{ route('schedules.index', ['type' => 'lv']) }}";
                            } else if (selected === 'lighting_tower') {
                                window.location.href =
                                    "{{ route('schedules.index', ['type' => 'lt']) }}";
                            } else if (selected === 'truck') {
                                window.location.href =
                                    "{{ route('schedules.index', ['type' => 'tk']) }}";
                            } else if (selected === 'forklift') {
                                window.location.href =
                                    "{{ route('schedules.index', ['type' => 'fm']) }}";
                            } else if (selected === 'pumps') {
                                window.location.href =
                                    "{{ route('schedules.index', ['type' => 'pm']) }}";
                            }
                        });
                    });
                }
            });
        });

        $('body').on('click', '.technicianMenuBtn', function() {
            Swal.fire({
                title: 'Select Asset Type',
                icon: 'question',
                html: `
                 <p>Nothing you choose here will affect today's live schedule.</p>
                <button class="swal2-confirm btn-option btn btn-sm btn-primary" data-value="light_vehicle">Light Vehicles</button>
                <button class="swal2-confirm btn-option btn btn-sm btn-secondary" data-value="lighting_tower">Lighting Towers</button>
                <button class="swal2-confirm btn-option btn btn-sm btn-success" data-value="truck">Truck</button>
                   <button class="swal2-confirm btn-option btn btn-sm btn-info" data-value="forklift">Forklift / Manitou</button>
                <button class="swal2-confirm btn-option btn btn-sm btn-warning" data-value="pumps">Pumps</button>
              `,
                showConfirmButton: false,
                didOpen: () => {
                    document.querySelectorAll('.btn-option').forEach(button => {
                        button.addEventListener('click', () => {
                            const selected = button.getAttribute('data-value');
                            Swal.close();
                            if (selected === 'light_vehicle') {
                                window.location.href =
                                    "{{ route('technicians.index', ['type' => 'lv']) }}";
                            } else if (selected === 'lighting_tower') {
                                window.location.href =
                                    "{{ route('technicians.index', ['type' => 'lt']) }}";
                            } else if (selected === 'truck') {
                                window.location.href =
                                    "{{ route('technicians.index', ['type' => 'tk']) }}";
                            } else if (selected === 'forklift') {
                                window.location.href =
                                    "{{ route('technicians.index', ['type' => 'fm']) }}";
                            } else if (selected === 'pumps') {
                                window.location.href =
                                    "{{ route('technicians.index', ['type' => 'pm']) }}";
                            }
                        });
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tooltipText =
                `Upload all completed prestarts or carry out new online prestarts.
Prestarts for any equipment can be tailored to suit. It is a simple process to modify the prestarts.
All prestarts are checked by competent personnel, all data including hours, kilometers or other readings used for scheduling are recorded and submitted to the scheduling program, work orders are raised for repairs or servicing and then the records are archived to ensure compliance with the Work Health and Safety (Mines) Regulations.`;

            const tooltipElements = document.querySelectorAll('[data-key="t-company"]');

            tooltipElements.forEach(function(el) {
                el.setAttribute('title', tooltipText);
                el.setAttribute('data-bs-title', tooltipText);

                new bootstrap.Tooltip(el, {
                    customClass: 'custom-tooltip'
                });
            });
        });
    </script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                notify('error', "{{ $error }}");
            </script>
        @endforeach
    @endif
    <script>
        @if (Session::has('success'))
            notify('success', "{{ session('success') }}");
        @elseif (Session::has('error'))
            notify('error', "{{ Session::get('error') }}");
        @elseif (Session::has('warning'))
            notify('warning', "{{ Session::get('warning') }}");
        @elseif (Session::has('info'))
            notify('info', "{{ Session::get('info') }}");
        @endif

        @foreach (session('toasts', collect())->toArray() as $toast)
            const options = {
                title: '{{ $toast['title'] ?? '' }}',
                message: '{{ $toast['message'] ?? 'No message provided' }}',
                position: '{{ $toast['position'] ?? 'topRight' }}',
            };
            show('{{ $toast['type'] ?? 'info' }}', options);
        @endforeach

        function notify(type, msg, position = 'toast-top-right') {
            if (['success', 'info', 'warning', 'error'].includes(type)) {
                toastr.options = {
                    closeButton: true,
                    positionClass: position,
                    progressBar: true
                };
                toastr[type](msg);
            } else {
                console.error(`Invalid toastr type: ${type}`);
            }
        }

        function show(type, options) {
            if (['info', 'success', 'warning', 'error'].includes(type)) {
                toastr[type](options);
            } else {
                toastr.show(options);
            }
        }
    </script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('vendor-script')
    @yield('page-script')

</body>

</html>
