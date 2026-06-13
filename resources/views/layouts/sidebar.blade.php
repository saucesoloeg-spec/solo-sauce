<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 {{ app()->getLocale() == 'ar' ? 'fixed-end rotate-caret' : 'fixed-start' }} " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav">
        </i>
        <a class="navbar-brand d-flex align-items-center m-0" href="#">
            <img src="{{ asset('assets/img/white_logo.png') }}" class="navbar-brand-img" alt="main_logo" style="width: 40px; height: 100px;">
            <span class="font-weight-bold text-lg">Solo Sauce</span>
        </a>
    </div>
    <div class="collapse navbar-collapse px-4 w-auto" id="sidenav-collapse-main">
        @php $role = Auth::guard('admin')->user()->role ?? null; @endphp
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                    <svg width="30px" height="30px" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>dashboard</title>
                        <g id="dashboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="template" transform="translate(12.000000, 12.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <path class="color-foreground" d="M0,1.71428571 C0,0.76752 0.76752,0 1.71428571,0 L22.2857143,0 C23.2325143,0 24,0.76752 24,1.71428571 L24,5.14285714 C24,6.08962286 23.2325143,6.85714286 22.2857143,6.85714286 L1.71428571,6.85714286 C0.76752,6.85714286 0,6.08962286 0,5.14285714 L0,1.71428571 Z" id="Path"></path>
                            <path class="color-background" d="M0,12 C0,11.0532171 0.76752,10.2857143 1.71428571,10.2857143 L12,10.2857143 C12.9468,10.2857143 13.7142857,11.0532171 13.7142857,12 L13.7142857,22.2857143 C13.7142857,23.2325143 12.9468,24 12,24 L1.71428571,24 C0.76752,24 0,23.2325143 0,22.2857143 L0,12 Z" id="Path"></path>
                            <path class="color-background" d="M18.8571429,10.2857143 C17.9103429,10.2857143 17.1428571,11.0532171 17.1428571,12 L17.1428571,22.2857143 C17.1428571,23.2325143 17.9103429,24 18.8571429,24 L22.2857143,24 C23.2325143,24 24,23.2325143 24,22.2857143 L24,12 C24,11.0532171 23.2325143,10.2857143 22.2857143,10.2857143 L18.8571429,10.2857143 Z" id="Path"></path>
                        </g>
                        </g>
                    </svg>
                    </div>
                    <span class="nav-link-text ms-1">{{ __('dashboard.dashboard') }}</span>
                </a>
            </li>
            @if ($role === 'manager')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('manager.orders.get') }}">
                        <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFFFFF" viewBox="0 0 24 24">
                                <path d="M3 3h18v4H3V3zm0 7h18v4H3v-4zm0 7h18v4H3v-4z"/>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">{{ __('dashboard.manage_orders') }}</span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customers.get') }}">
                        <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <title>Customers</title>
                                <g fill="#FFFFFF" fill-rule="nonzero">
                                    <path d="M9 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 7c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm6 5H3v-2c0-1.5 3.58-2.5 6-2.5s6 1 6 2.5v2zm3-4v-3h-3V9h3V6h2v3h3v2h-3v3h-2z"></path>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">{{ __('dashboard.customers') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sales.get') }}">
                        <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFFFFF" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">{{ __('dashboard.representatives') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('schedules.get') }}">
                        <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <title>Customize Representative</title>
                                <g fill="#FFFFFF" fill-rule="nonzero">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zm-5-5H7v5h7v-5z"></path>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">{{ __('dashboard.schedule') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.get') }}">
                        <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFFFFF" viewBox="0 0 16 16">
                                <path d="M0 0h1v15h15v1H0V0zm10 10h2v4h-2v-4zm-4-6h2v10H6V4zM2 8h2v6H2V8z"/>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">{{ __('dashboard.reports') }}</span>
                    </a>
                </li>

                @if ($role === 'super_admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admins.get') }}">
                            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                                <svg width="30px" height="30px" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>Admins</title>
                                    <g id="dashboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="template" transform="translate(12.000000, 12.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                            <path class="color-foreground" d="M0,1.71428571 C0,0.76752 0.76752,0 1.71428571,0 L22.2857143,0 C23.2325143,0 24,0.76752 24,1.71428571 L24,5.14285714 C24,6.08962286 23.2325143,6.85714286 22.2857143,6.85714286 L1.71428571,6.85714286 C0.76752,6.85714286 0,6.08962286 0,5.14285714 L0,1.71428571 Z" id="Path"></path>
                                            <path class="color-background" d="M0,12 C0,11.0532171 0.76752,10.2857143 1.71428571,10.2857143 L12,10.2857143 C12.9468,10.2857143 13.7142857,11.0532171 13.7142857,12 L13.7142857,22.2857143 C13.7142857,23.2325143 12.9468,24 12,24 L1.71428571,24 C0.76752,24 0,23.2325143 0,22.2857143 L0,12 Z" id="Path"></path>
                                            <path class="color-background" d="M18.8571429,10.2857143 C17.9103429,10.2857143 17.1428571,11.0532171 17.1428571,12 L17.1428571,22.2857143 C17.1428571,23.2325143 17.9103429,24 18.8571429,24 L22.2857143,24 C23.2325143,24 24,23.2325143 24,22.2857143 L24,12 C24,11.0532171 23.2325143,10.2857143 22.2857143,10.2857143 L18.8571429,10.2857143 Z" id="Path"></path>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('dashboard.admins') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('messages.get') }}">
                            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#FFFFFF" viewBox="0 0 24 24">
                                    <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22zm6-6V11a6 6 0 1 0-12 0v5l-2 2v1h16v-1l-2-2zm-9.5-5a.5.5 0 0 1-.5-.5A4.5 4.5 0 0 1 12.5 6a.5.5 0 0 1 0 1A3.5 3.5 0 0 0 9 10.5a.5.5 0 0 1-.5.5z"/>
                                </svg>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('dashboard.messages') }}</span>
                        </a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
    <div style="width: 100%;text-align: center;position: relative;bottom: -150px">
        <form action="{{ url('lang/' . (app()->getLocale() == 'en' ? 'ar' : 'en')) }}" method="GET" class="language-switch-form">
            <label class="switch">
                <input type="checkbox" onchange="this.form.submit()" {{ app()->getLocale() == 'ar' ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </form>
    </div>
</aside>