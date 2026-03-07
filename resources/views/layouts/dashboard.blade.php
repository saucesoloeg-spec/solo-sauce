<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="../assets/img/favicon.png">
        <title>
            Solo Sauce
        </title>
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700" rel="stylesheet" />
        <!-- Nucleo Icons -->
        <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
        <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/349ee9c857.js" crossorigin="anonymous"></script>
        <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
        <!-- CSS Files -->
        <link id="pagestyle" href="../assets/css/corporate-ui-dashboard.css?v=1.0.0" rel="stylesheet" />

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <style>
            /* Style the switch */
            .switch {
                position: relative;
                display: inline-block;
                width: 85px;
                height: 35px;
            }

            /* Hide the default checkbox */
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            /* Create the slider */
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: 0.4s;
                border-radius: 34px;
            }

            /* The circle inside the slider */
            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                border-radius: 50%;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: 0.4s;
            }

            /* When checked, change the background and move the circle */
            input:checked + .slider {
                background-color: #4CAF50;
            }

            input:checked + .slider:before {
                transform: translateX(45px);
            }

            /* Rounded corners for the slider */
            .slider.round {
                border-radius: 34px;
            }

            /* When the switch is checked, change the language text */
            input:checked + .slider:after {
                margin-right: 35px;
                content: 'عربي';
                position: absolute;
                top: 50%;
                right: 10px; /* Move text to the right side */
                transform: translateY(-50%);
                font-size: 12px;
                color: white;
            }

            /* Display English text when the switch is not checked */
            input:not(:checked) + .slider:after {
                margin-left: 22px;
                content: 'English';
                position: absolute;
                top: 50%;
                left: 10px; /* Move text to the left side */
                transform: translateY(-50%);
                font-size: 12px;
                color: white;
            }

        </style>

        @yield('CSS')

    </head>
    <body class="g-sidenav-show {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} bg-gray-100">
        @include('layouts.sidebar')
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            
            @include('layouts.navbar')
            <div class="container-fluid py-4 px-5">

                @yield('content')

            </div>

        </main>

        @include('layouts.preference')

        <!--   Core JS Files   -->
        <script src="../assets/js/core/popper.min.js"></script>
        <script src="../assets/js/core/bootstrap.min.js"></script>
        <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
        <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
        <script src="../assets/js/plugins/chartjs.min.js"></script>
        <script src="../assets/js/plugins/swiper-bundle.min.js" type="text/javascript"></script>
        
        @yield('JavaScript')
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const navLinks = document.querySelectorAll(".nav-link");

                navLinks.forEach(link => {
                    if (link.href === window.location.href) {
                        link.classList.add("active");
                    } else {
                        link.classList.remove("active");
                    }

                    link.addEventListener("click", function () {
                        navLinks.forEach(l => l.classList.remove("active"));
                        this.classList.add("active");
                    });
                });

                const path = window.location.pathname;
                const pathSegments = path.split('/').filter(segment => segment !== '');
                let breadcrumbHTML = `<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Dashboard</a></li>`;
                let title = 'Dashboard';

                pathSegments.forEach((segment, index) => {
                    let displaySegment = segment.charAt(0).toUpperCase() + segment.slice(1);

                    // Replace "offers" with "Requests"
                    if (segment.toLowerCase() === 'offers') {
                        displaySegment = 'Requests';
                    }

                    if (index === pathSegments.length - 1) {
                        // Check if the company name is available
                        const companyNameElement = document.getElementById('page-data');
                        const companyName = companyNameElement ? companyNameElement.getAttribute('data-company-name') : null;

                        const displayName = companyName && displaySegment === '1' ? companyName : displaySegment;

                        breadcrumbHTML += `<li class="breadcrumb-item text-sm text-dark active" aria-current="page">${displayName}</li>`;
                        title = displayName; // Set title to the company name or last segment
                    } else {
                        breadcrumbHTML += `<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">${displaySegment}</a></li>`;
                    }
                });

                document.getElementById('breadcrumb').innerHTML = breadcrumbHTML;
                document.getElementById('breadcrumb-title').innerText = title;
            });


            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
        </script>
        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="../assets/js/corporate-ui-dashboard.min.js?v=1.0.0"></script>
    </body>
</html>