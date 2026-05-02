@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4 px-5">

    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex align-items-center mb-3 mx-2">
                <div class="mb-md-0 mb-3">
                    <h3 class="font-weight-bold mb-0">{{ __('dashboard.hello', ['name' => $admin->name]) }}</h3>
                    <p class="mb-0">{{ __('dashboard.statement') }}</p>
                </div>
                <!-- <button type="button" class="btn btn-sm btn-white btn-icon d-flex align-items-center mb-0 ms-md-auto mb-sm-0 mb-2 me-2">
                    <span class="btn-inner--icon">
                        <span class="p-1 bg-success rounded-circle d-flex ms-auto me-2">
                            <span class="visually-hidden">New</span>
                        </span>
                    </span>
                    <span class="btn-inner--text">Messages</span>
                </button>
                <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0">
                    <span class="btn-inner--icon">
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-block me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </span>
                    <span class="btn-inner--text">Sync</span>
                </button> -->
            </div>
        </div>
    </div>

    <hr class="mt-0 mb-5">

    <div class="row">
        <div class="col-xl-2 col-sm-3 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3.75a.75.75 0 01.53.22l8.25 8.25a.75.75 0 11-1.06 1.06L18.75 12.31V19.5A2.25 2.25 0 0116.5 21.75h-3.75a.75.75 0 01-.75-.75v-3.75h-1.5V21a.75.75 0 01-.75.75H7.5A2.25 2.25 0 015.25 19.5v-7.19l-.97.97a.75.75 0 11-1.06-1.06l8.25-8.25A.75.75 0 0112 3.75z"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.total_visits') }}</p>
                                <h4 class="mb-2 font-weight-bold"> {{$monthly_income->total_visits ?? 0}} </h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->total_visits >= $past_monthly_income->total_visits)
                                    <span class="text-sm text-success font-weight-bolder">
                                        <i class="fa fa-chevron-up text-xs me-1"></i> +{{ $monthly_income->total_visits ? round((($monthly_income->total_visits - $past_monthly_income->total_visits) / $past_monthly_income->total_visits) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                        <i class="fa fa-chevron-down text-xs me-1"></i> -{{ $monthly_income->total_visits ? round((($past_monthly_income->total_visits - $monthly_income->total_visits) / $past_monthly_income->total_visits) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{$past_monthly_income->total_visits ?? 0}} {{__('dashboard.previous_month')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-sm-3 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <!-- Rounded square background -->
                            <rect x="0" y="0" width="48" height="48" rx="12" fill="#22C58B"/>
                            <!-- White circle -->
                            <circle cx="24" cy="24" r="10" fill="none" stroke="white" stroke-width="2"/>
                            <!-- Check mark -->
                            <path d="M20.5 24.5l2.5 2.5 5-5" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.completed_visits') }}</p>
                                <h4 class="mb-2 font-weight-bold"> {{ $monthly_income->completed_visits ?? 0 }} </h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->completed_visits >= $past_monthly_income->completed_visits)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->completed_visits ? round((($monthly_income->completed_visits - $past_monthly_income->completed_visits) / $past_monthly_income->completed_visits) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->completed_visits ? round((($past_monthly_income->completed_visits - $monthly_income->completed_visits) / $past_monthly_income->completed_visits) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{ $past_monthly_income->completed_visits ?? 0 }} {{__('dashboard.previous_month')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-sm-3 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background -->
                            <rect width="48" height="48" rx="12" fill="#3B82F6"/>
                            <!-- Circle -->
                            <circle cx="24" cy="24" r="10" fill="none" stroke="white" stroke-width="2"/>
                            <!-- Clock icon -->
                            <path d="M24 19v5l3 2" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.opened') }}</p>
                                <h4 class="mb-2 font-weight-bold"> {{ $monthly_income->open_visits ?? 0 }} </h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->open_visits >= $past_monthly_income->open_visits)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->open_visits ? round((($monthly_income->open_visits - $past_monthly_income->open_visits) / $past_monthly_income->open_visits) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->open_visits ? round((($past_monthly_income->open_visits - $monthly_income->open_visits) / $past_monthly_income->open_visits) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{ $past_monthly_income->open_visits ?? 0 }} {{__('dashboard.previous_month')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-sm-3 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background -->
                            <rect width="48" height="48" rx="12" fill="#F59E0B"/>
                            <!-- Circle -->
                            <circle cx="24" cy="24" r="10" fill="none" stroke="white" stroke-width="2"/>
                            <!-- Exclamation mark -->
                            <path d="M24 18v6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="24" cy="27.5" r="1" fill="white"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.delayed') }}</p>
                                <h4 class="mb-2 font-weight-bold"> {{ $monthly_income->delayed ?? 0 }} </h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->delayed >= $past_monthly_income->delayed)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->delayed ? round((($monthly_income->delayed - $past_monthly_income->delayed) / $past_monthly_income->delayed) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->delayed ? round((($past_monthly_income->delayed - $monthly_income->delayed) / $past_monthly_income->delayed) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{$past_monthly_income->delayed ?? 0}} {{ __('dashboard.previous_month') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-sm-3 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background -->
                            <rect width="48" height="48" rx="12" fill="#EF4444"/>
                            <!-- Circle -->
                            <circle cx="24" cy="24" r="10" fill="none" stroke="white" stroke-width="2"/>
                            <!-- X mark -->
                            <path d="M21 21l6 6M27 21l-6 6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.cancelled') }}</p>
                                <h4 class="mb-2 font-weight-bold"> {{ $monthly_income->cancelled ?? 0 }} </h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->cancelled >= $past_monthly_income->cancelled)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->cancelled ? round((($monthly_income->cancelled - $past_monthly_income->cancelled) / ($past_monthly_income->cancelled ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->cancelled ? round((($past_monthly_income->cancelled - $monthly_income->cancelled) / ($past_monthly_income->cancelled ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{$past_monthly_income->cancelled ?? 0}} {{ __('dashboard.previous_month') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-sm-3 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background -->
                        <rect width="48" height="48" rx="12" fill="#111827"/> 
                        <!-- Dollar sign (bigger + cleaner) -->
                        <path d="M24 14v20
                                M29 19c0-2-2-3.5-5-3.5s-5 1.5-5 3.5 2 3.5 5 3.5 5 1.5 5 3.5-2 3.5-5 3.5-5-1.5-5-3.5"
                                fill="none"
                                stroke="white"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.total_monthly_income') }}</p>
                                <h4 class="mb-2 font-weight-bold">{{ $monthly_income->income ?? 0 }}</h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->income >= $past_monthly_income->income)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->income ? round((($monthly_income->income - $past_monthly_income->income) / ($past_monthly_income->income ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->income ? round((($past_monthly_income->income - $monthly_income->income) / ($past_monthly_income->income ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} ${{$monthly_income->income ?? 0}}  {{__('dashboard.previous_month')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background -->
                            <rect width="48" height="48" rx="12" fill="#1F2937"/>
                            <!-- List container -->
                            <rect x="14" y="14" width="20" height="20" rx="3" fill="none" stroke="white" stroke-width="2"/>
                            <!-- Bullets -->
                            <circle cx="18" cy="20" r="1.5" fill="white"/>
                            <circle cx="18" cy="24" r="1.5" fill="white"/>
                            <circle cx="18" cy="28" r="1.5" fill="white"/>
                            <!-- Lines -->
                            <path d="M22 20h8M22 24h8M22 28h8" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.surveys') }}</p>
                                <h4 class="mb-2 font-weight-bold">{{ $monthly_income->total_surveys ?? 0 }}</h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->total_surveys >= $past_monthly_income->total_surveys)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->total_surveys ? round((($monthly_income->total_surveys - $past_monthly_income->total_surveys) / ($past_monthly_income->total_surveys ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->total_surveys ? round((($past_monthly_income->total_surveys - $monthly_income->total_surveys) / ($past_monthly_income->total_surveys ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{ $past_monthly_income->total_surveys ?? 0 }} {{__('dashboard.previous_month')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background -->
                            <rect width="48" height="48" rx="12" fill="#1F2937"/>
                            <!-- Circular arrow -->
                            <path d="M32 24a8 8 0 10-2.3 5.7" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                            <!-- Arrow head -->
                            <path d="M30 17v6h6" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.reorders') }}</p>
                                <h4 class="mb-2 font-weight-bold">{{ $monthly_income->total_reorders ?? 0 }}</h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->total_reorders >= $past_monthly_income->total_reorders)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->total_reorders ? round((($monthly_income->total_reorders - $past_monthly_income->total_reorders) / ($past_monthly_income->total_reorders ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->total_reorders ? round((($past_monthly_income->total_reorders - $monthly_income->total_reorders) / ($past_monthly_income->total_reorders ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{ $past_monthly_income->total_reorders ?? 0 }} {{__('dashboard.previous_month')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0">
            <div class="card border shadow-xs mb-4">
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background -->
                            <rect width="48" height="48" rx="12" fill="#1F2937"/>
                            <!-- Inner badge -->
                            <rect x="12" y="16" width="24" height="16" rx="4" fill="white"/>
                            <!-- Text -->
                            <text x="24" y="27" text-anchor="middle" font-size="10" font-family="Arial, sans-serif" fill="#1F2937" font-weight="bold">
                                NEW
                            </text>
                        </svg>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100">
                                <p class="text-sm text-secondary mb-1">{{ __('dashboard.new_deals') }}</p>
                                <h4 class="mb-2 font-weight-bold">{{ $monthly_income->total_new_orders ?? 0 }}</h4>
                                <div class="d-flex align-items-center">
                                    @if($monthly_income->total_new_orders >= $past_monthly_income->total_new_orders)
                                    <span class="text-sm text-success font-weight-bolder">
                                    <i class="fa fa-chevron-up text-xs me-1"></i> {{ $monthly_income->total_new_orders ? round((($monthly_income->total_new_orders - $past_monthly_income->total_new_orders) / ($past_monthly_income->total_new_orders ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @else
                                    <span class="text-sm text-danger font-weight-bolder">
                                    <i class="fa fa-chevron-down text-xs me-1"></i> {{ $monthly_income->total_new_orders ? round((($past_monthly_income->total_new_orders - $monthly_income->total_new_orders) / ($past_monthly_income->total_new_orders ?: 1)) * 100, 2) : 0 }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-sm ms-1">{{__('dashboard.from')}} {{ $past_monthly_income->total_new_orders ?? 0 }} {{__('dashboard.previous_month')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row my-4">
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
            <div class="card shadow-xs border h-100">
                <div class="card-header pb-0">
                    <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.reorder_by_representative') }}</h6>
                    <p class="text-sm">{{ __('dashboard.reorder_by_representative_description') }}</p>
                    <!-- <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-white px-3 mb-0" for="btnradio1">12 months</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio2">30 days</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio3">7 days</label>
                    </div> -->
                </div>
                <div class="card-body py-3">
                    <div class="reorder_chart mb-2">
                        <canvas id="reorder_chart-bars" class="chart-canvas" height="240"></canvas>
                    </div>
                    <!-- <button class="btn btn-white mb-0 ms-auto">View report</button> -->
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
            <div class="card shadow-xs border h-100">
                <div class="card-header pb-0">
                    <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.new_deals_by_representative') }}</h6>
                    <p class="text-sm">{{ __('dashboard.new_deals_by_representative_description') }}</p>
                    <!-- <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-white px-3 mb-0" for="btnradio1">12 months</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio2">30 days</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio3">7 days</label>
                    </div> -->
                </div>
                <div class="card-body py-3">
                    <div class="new_deals_chart mb-2">
                        <canvas id="new_deals_chart-bars" class="chart-canvas" height="240"></canvas>
                    </div>
                    <!-- <button class="btn btn-white mb-0 ms-auto">View report</button> -->
                </div>
            </div>
        </div>
        <!-- <div class="col-lg-8 col-md-6">
            <div class="card shadow-xs border">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-center mb-3">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Recent transactions</h6>
                            <p class="text-sm mb-sm-0 mb-2">These are details about the last transactions</p>
                        </div>
                        <div class="ms-auto d-flex">
                            <button type="button" class="btn btn-sm btn-white mb-0 me-2">
                            View report
                            </button>
                            <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0">
                                <span class="btn-inner--icon">
                                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-block me-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </span>
                                <span class="btn-inner--text">Download</span>
                            </button>
                        </div>
                    </div>
                    <div class="pb-3 d-sm-flex align-items-center">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable1" autocomplete="off" checked>
                            <label class="btn btn-white px-3 mb-0" for="btnradiotable1">All</label>
                            <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable2" autocomplete="off">
                            <label class="btn btn-white px-3 mb-0" for="btnradiotable2">Monitored</label>
                            <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable3" autocomplete="off">
                            <label class="btn btn-white px-3 mb-0" for="btnradiotable3">Unmonitored</label>
                        </div>
                        <div class="input-group w-sm-25 ms-auto">
                            <span class="input-group-text text-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                </svg>
                            </span>
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 py-0">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">Transaction</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Amount</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Date</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Account</th>
                                    <th class="text-center text-secondary text-xs font-weight-semibold opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2">
                                            <div class="avatar avatar-sm rounded-circle bg-gray-100 me-2 my-2">
                                                <img src="../assets/img/small-logos/logo-spotify.svg" class="w-80" alt="spotify">
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm">Spotify</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-normal mb-0">$2,500</p>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-normal">Wed 3:00pm</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <div class="border px-1 py-1 text-center d-flex align-items-center border-radius-sm my-auto">
                                                <img src="../assets/img/logos/visa.png" class="w-90 mx-auto" alt="visa">
                                            </div>
                                            <div class="ms-2">
                                                <p class="text-dark text-sm mb-0">Visa 1234</p>
                                                <p class="text-secondary text-sm mb-0">Expiry 06/2026</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Edit user">
                                            <svg width="14" height="14" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.2201 2.02495C10.8292 1.63482 10.196 1.63545 9.80585 2.02636C9.41572 2.41727 9.41635 3.05044 9.80726 3.44057L11.2201 2.02495ZM12.5572 6.18502C12.9481 6.57516 13.5813 6.57453 13.9714 6.18362C14.3615 5.79271 14.3609 5.15954 13.97 4.7694L12.5572 6.18502ZM11.6803 1.56839L12.3867 2.2762L12.3867 2.27619L11.6803 1.56839ZM14.4302 4.31284L15.1367 5.02065L15.1367 5.02064L14.4302 4.31284ZM3.72198 15V16C3.98686 16 4.24091 15.8949 4.42839 15.7078L3.72198 15ZM0.999756 15H-0.000244141C-0.000244141 15.5523 0.447471 16 0.999756 16L0.999756 15ZM0.999756 12.2279L0.293346 11.5201C0.105383 11.7077 -0.000244141 11.9624 -0.000244141 12.2279H0.999756ZM9.80726 3.44057L12.5572 6.18502L13.97 4.7694L11.2201 2.02495L9.80726 3.44057ZM12.3867 2.27619C12.7557 1.90794 13.3549 1.90794 13.7238 2.27619L15.1367 0.860593C13.9869 -0.286864 12.1236 -0.286864 10.9739 0.860593L12.3867 2.27619ZM13.7238 2.27619C14.0917 2.64337 14.0917 3.23787 13.7238 3.60504L15.1367 5.02064C16.2875 3.8721 16.2875 2.00913 15.1367 0.860593L13.7238 2.27619ZM13.7238 3.60504L3.01557 14.2922L4.42839 15.7078L15.1367 5.02065L13.7238 3.60504ZM3.72198 14H0.999756V16H3.72198V14ZM1.99976 15V12.2279H-0.000244141V15H1.99976ZM1.70617 12.9357L12.3867 2.2762L10.9739 0.86059L0.293346 11.5201L1.70617 12.9357Z" fill="#64748B" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2">
                                            <div class="avatar avatar-sm rounded-circle bg-gray-100 me-2 my-2">
                                                <img src="../assets/img/small-logos/logo-invision.svg" class="w-80" alt="invision">
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm">Invision</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-normal mb-0">$5,000</p>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-normal">Wed 1:00pm</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <div class="border px-1 py-1 text-center d-flex align-items-center border-radius-sm my-auto">
                                                <img src="../assets/img/logos/mastercard.png" class="w-90 mx-auto" alt="mastercard">
                                            </div>
                                            <div class="ms-2">
                                                <p class="text-dark text-sm mb-0">Mastercard 1234</p>
                                                <p class="text-secondary text-sm mb-0">Expiry 06/2026</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Edit user">
                                            <svg width="14" height="14" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.2201 2.02495C10.8292 1.63482 10.196 1.63545 9.80585 2.02636C9.41572 2.41727 9.41635 3.05044 9.80726 3.44057L11.2201 2.02495ZM12.5572 6.18502C12.9481 6.57516 13.5813 6.57453 13.9714 6.18362C14.3615 5.79271 14.3609 5.15954 13.97 4.7694L12.5572 6.18502ZM11.6803 1.56839L12.3867 2.2762L12.3867 2.27619L11.6803 1.56839ZM14.4302 4.31284L15.1367 5.02065L15.1367 5.02064L14.4302 4.31284ZM3.72198 15V16C3.98686 16 4.24091 15.8949 4.42839 15.7078L3.72198 15ZM0.999756 15H-0.000244141C-0.000244141 15.5523 0.447471 16 0.999756 16L0.999756 15ZM0.999756 12.2279L0.293346 11.5201C0.105383 11.7077 -0.000244141 11.9624 -0.000244141 12.2279H0.999756ZM9.80726 3.44057L12.5572 6.18502L13.97 4.7694L11.2201 2.02495L9.80726 3.44057ZM12.3867 2.27619C12.7557 1.90794 13.3549 1.90794 13.7238 2.27619L15.1367 0.860593C13.9869 -0.286864 12.1236 -0.286864 10.9739 0.860593L12.3867 2.27619ZM13.7238 2.27619C14.0917 2.64337 14.0917 3.23787 13.7238 3.60504L15.1367 5.02064C16.2875 3.8721 16.2875 2.00913 15.1367 0.860593L13.7238 2.27619ZM13.7238 3.60504L3.01557 14.2922L4.42839 15.7078L15.1367 5.02065L13.7238 3.60504ZM3.72198 14H0.999756V16H3.72198V14ZM1.99976 15V12.2279H-0.000244141V15H1.99976ZM1.70617 12.9357L12.3867 2.2762L10.9739 0.86059L0.293346 11.5201L1.70617 12.9357Z" fill="#64748B" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2">
                                            <div class="avatar avatar-sm rounded-circle bg-gray-100 me-2 my-2">
                                                <img src="../assets/img/small-logos/logo-jira.svg" class="w-80" alt="jira">
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm">Jira</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-normal mb-0">$3,400</p>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-normal">Mon 7:40pm</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <div class="border px-1 py-1 text-center d-flex align-items-center border-radius-sm my-auto">
                                                <img src="../assets/img/logos/mastercard.png" class="w-90 mx-auto" alt="mastercard">
                                            </div>
                                            <div class="ms-2">
                                                <p class="text-dark text-sm mb-0">Mastercard 1234</p>
                                                <p class="text-secondary text-sm mb-0">Expiry 06/2026</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Edit user">
                                            <svg width="14" height="14" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.2201 2.02495C10.8292 1.63482 10.196 1.63545 9.80585 2.02636C9.41572 2.41727 9.41635 3.05044 9.80726 3.44057L11.2201 2.02495ZM12.5572 6.18502C12.9481 6.57516 13.5813 6.57453 13.9714 6.18362C14.3615 5.79271 14.3609 5.15954 13.97 4.7694L12.5572 6.18502ZM11.6803 1.56839L12.3867 2.2762L12.3867 2.27619L11.6803 1.56839ZM14.4302 4.31284L15.1367 5.02065L15.1367 5.02064L14.4302 4.31284ZM3.72198 15V16C3.98686 16 4.24091 15.8949 4.42839 15.7078L3.72198 15ZM0.999756 15H-0.000244141C-0.000244141 15.5523 0.447471 16 0.999756 16L0.999756 15ZM0.999756 12.2279L0.293346 11.5201C0.105383 11.7077 -0.000244141 11.9624 -0.000244141 12.2279H0.999756ZM9.80726 3.44057L12.5572 6.18502L13.97 4.7694L11.2201 2.02495L9.80726 3.44057ZM12.3867 2.27619C12.7557 1.90794 13.3549 1.90794 13.7238 2.27619L15.1367 0.860593C13.9869 -0.286864 12.1236 -0.286864 10.9739 0.860593L12.3867 2.27619ZM13.7238 2.27619C14.0917 2.64337 14.0917 3.23787 13.7238 3.60504L15.1367 5.02064C16.2875 3.8721 16.2875 2.00913 15.1367 0.860593L13.7238 2.27619ZM13.7238 3.60504L3.01557 14.2922L4.42839 15.7078L15.1367 5.02065L13.7238 3.60504ZM3.72198 14H0.999756V16H3.72198V14ZM1.99976 15V12.2279H-0.000244141V15H1.99976ZM1.70617 12.9357L12.3867 2.2762L10.9739 0.86059L0.293346 11.5201L1.70617 12.9357Z" fill="#64748B" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2">
                                            <div class="avatar avatar-sm rounded-circle bg-gray-100 me-2 my-2">
                                                <img src="../assets/img/small-logos/logo-slack.svg" class="w-80" alt="slack">
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm">Slack</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-normal mb-0">$1,000</p>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-normal">Wed 5:00pm</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <div class="border px-1 py-1 text-center d-flex align-items-center border-radius-sm my-auto">
                                                <img src="../assets/img/logos/visa.png" class="w-90 mx-auto" alt="visa">
                                            </div>
                                            <div class="ms-2">
                                                <p class="text-dark text-sm mb-0">Visa 1234</p>
                                                <p class="text-secondary text-sm mb-0">Expiry 06/2026</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Edit user">
                                            <svg width="14" height="14" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.2201 2.02495C10.8292 1.63482 10.196 1.63545 9.80585 2.02636C9.41572 2.41727 9.41635 3.05044 9.80726 3.44057L11.2201 2.02495ZM12.5572 6.18502C12.9481 6.57516 13.5813 6.57453 13.9714 6.18362C14.3615 5.79271 14.3609 5.15954 13.97 4.7694L12.5572 6.18502ZM11.6803 1.56839L12.3867 2.2762L12.3867 2.27619L11.6803 1.56839ZM14.4302 4.31284L15.1367 5.02065L15.1367 5.02064L14.4302 4.31284ZM3.72198 15V16C3.98686 16 4.24091 15.8949 4.42839 15.7078L3.72198 15ZM0.999756 15H-0.000244141C-0.000244141 15.5523 0.447471 16 0.999756 16L0.999756 15ZM0.999756 12.2279L0.293346 11.5201C0.105383 11.7077 -0.000244141 11.9624 -0.000244141 12.2279H0.999756ZM9.80726 3.44057L12.5572 6.18502L13.97 4.7694L11.2201 2.02495L9.80726 3.44057ZM12.3867 2.27619C12.7557 1.90794 13.3549 1.90794 13.7238 2.27619L15.1367 0.860593C13.9869 -0.286864 12.1236 -0.286864 10.9739 0.860593L12.3867 2.27619ZM13.7238 2.27619C14.0917 2.64337 14.0917 3.23787 13.7238 3.60504L15.1367 5.02064C16.2875 3.8721 16.2875 2.00913 15.1367 0.860593L13.7238 2.27619ZM13.7238 3.60504L3.01557 14.2922L4.42839 15.7078L15.1367 5.02065L13.7238 3.60504ZM3.72198 14H0.999756V16H3.72198V14ZM1.99976 15V12.2279H-0.000244141V15H1.99976ZM1.70617 12.9357L12.3867 2.2762L10.9739 0.86059L0.293346 11.5201L1.70617 12.9357Z" fill="#64748B" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <div class="row my-4">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card shadow-xs border h-100">
                <div class="card-header pb-0">
                    <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.surveys_by_representative') }}</h6>
                    <p class="text-sm">{{ __('dashboard.surveys_by_representative_description') }}</p>
                    <!-- <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-white px-3 mb-0" for="btnradio1">12 months</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio2">30 days</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio3">7 days</label>
                    </div> -->
                </div>
                <div class="card-body py-3">
                    <div class="reorder_chart mb-2">
                        <canvas id="surveys_chart-bars" class="chart-canvas" height="240"></canvas>
                    </div>
                    <!-- <button class="btn btn-white mb-0 ms-auto">View report</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-xs border">
                <div class="card-header pb-0">
                    <div class="d-sm-flex align-items-center mb-3">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.yearly_income') }}</h6>
                            <p class="text-sm mb-sm-0 mb-2">{{ __('dashboard.yearly_income_statement') }}</p>
                        </div>
                        <!-- <div class="ms-auto d-flex">
                            <button type="button" class="btn btn-sm btn-white mb-0 me-2">
                            View report
                            </button>
                        </div> -->
                    </div>
                    <div class="d-sm-flex align-items-center">
                        <h3 class="mb-4 font-weight-semibold">${{ $yearly_income->sum('income') ?? 0 }}</h3>
                        <span class="badge badge-sm border border-success text-success bg-success border-radius-sm ms-sm-3 px-2">
                            <svg width="9" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.46967 4.46967C0.176777 4.76256 0.176777 5.23744 0.46967 5.53033C0.762563 5.82322 1.23744 5.82322 1.53033 5.53033L0.46967 4.46967ZM5.53033 1.53033C5.82322 1.23744 5.82322 0.762563 5.53033 0.46967C5.23744 0.176777 4.76256 0.176777 4.46967 0.46967L5.53033 1.53033ZM5.53033 0.46967C5.23744 0.176777 4.76256 0.176777 4.46967 0.46967C4.17678 0.762563 4.17678 1.23744 4.46967 1.53033L5.53033 0.46967ZM8.46967 5.53033C8.76256 5.82322 9.23744 5.82322 9.53033 5.53033C9.82322 5.23744 9.82322 4.76256 9.53033 4.46967L8.46967 5.53033ZM1.53033 5.53033L5.53033 1.53033L4.46967 0.46967L0.46967 4.46967L1.53033 5.53033ZM4.46967 1.53033L8.46967 5.53033L9.53033 4.46967L5.53033 0.46967L4.46967 1.53033Z" fill="#67C23A"></path>
                            </svg>
                            10.5%
                        </span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart mt-n6">
                        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
            <div class="card shadow-xs border h-100">
                <div class="card-header pb-0">
                    <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.total_orders') }}</h6>
                    <p class="text-sm">{{ __('dashboard.total_orders_description') }}</p>
                    <!-- <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-white px-3 mb-0" for="btnradio1">12 months</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio2">30 days</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio3">7 days</label>
                    </div> -->
                </div>
                <div class="card-body py-3">
                    <div class="total_orders_chart mb-2">
                        <canvas id="total_orders_chart-bars" class="chart-canvas" height="240"></canvas>
                    </div>
                    <!-- <button class="btn btn-white mb-0 ms-auto">View report</button> -->
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
            <div class="card shadow-xs border h-100">
                <div class="card-header pb-0">
                    <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.total_orders_by_status') }}</h6>
                    <p class="text-sm">{{ __('dashboard.total_orders_by_status_description') }}</p>
                    <!-- <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-white px-3 mb-0" for="btnradio1">12 months</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio2">30 days</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="btnradio3">7 days</label>
                    </div> -->
                </div>
                <div class="card-body py-3">
                    <div class="total_orders_by_status_chart mb-2">
                        <canvas id="total_orders_by_status_chart-bars" class="chart-canvas" height="240"></canvas>
                    </div>
                    <!-- <button class="btn btn-white mb-0 ms-auto">View report</button> -->
                </div>
            </div>
        </div>
    </div>
    <!-- <footer class="footer pt-3  ">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="copyright text-center text-xs text-muted text-lg-start">
                        Copyright
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>
                        Corporate UI by
                        <a href="https://www.creative-tim.com" class="text-secondary" target="_blank">Creative Tim</a>.
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com" class="nav-link text-xs text-muted" target="_blank">Creative Tim</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/presentation" class="nav-link text-xs text-muted" target="_blank">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/blog" class="nav-link text-xs text-muted" target="_blank">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/license" class="nav-link text-xs pe-0 text-muted" target="_blank">License</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer> -->
</div>
@stop
@section('JavaScript')
<script>
    if (document.getElementsByClassName('mySwiper')) {
        var swiper = new Swiper(".mySwiper", {
            effect: "cards",
            grabCursor: true,
            initialSlide: 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    };

    const monthlyIncome = @json($monthly_income);
    const monthlyOrders = @json($monthly_orders);

    var reorder_ctx                = document.getElementById("reorder_chart-bars").getContext("2d");
    var new_deals_ctx              = document.getElementById("new_deals_chart-bars").getContext("2d");
    var surveys_ctx                = document.getElementById("surveys_chart-bars").getContext("2d");
    var total_orders_ctx           = document.getElementById("total_orders_chart-bars").getContext("2d");
    var total_orders_by_status_ctx = document.getElementById("total_orders_by_status_chart-bars").getContext("2d");

    new Chart(reorder_ctx, {
        type: "bar",
        data: {
            labels: ["Ahmad", "Mohamad", "Zain", "Islam", "Yasser", "Ali", "Khaled", "Mona", "Nada", "Sarah"],
            datasets: [{
                label: "Reorders",
                tension: 0.4,
                borderWidth: 0,
                borderSkipped: false,
                backgroundColor: "rgba(56, 189, 248, 0.75)",
                borderColor: "rgba(56, 189, 248, 1)",
                data: [450, 200, 100, 220, 500, 100, 400, 230, 500, 200],
                maxBarThickness: 10
            },
            {
                label: "Sales",
                tension: 0.4,
                borderWidth: 0,
                borderSkipped: 'bottom',
                borderRadius: {
                    topLeft: 2,
                    topRight: 2,
                    bottomLeft: 0,
                    bottomRight: 0
                },
                backgroundColor: "rgba(124, 58, 237, 0.75)",
                borderColor: "rgba(124, 58, 237, 1)",
                data: [200, 300, 200, 420, 400, 200, 300, 430, 400, 300],
                maxBarThickness: 10
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e9ecef',
                    borderWidth: 1,
                    usePointStyle: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [4, 4],
                    },
                    ticks: {
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
                x: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
            },
        },
    });

    new Chart(new_deals_ctx, {
        type: "bar",
        data: {
            labels: ["Ahmad", "Mohamad", "Zain", "Islam", "Yasser", "Ali", "Khaled", "Mona", "Nada", "Sarah"],
            datasets: [{
                    label: "New Deals",
                    tension: 0.4,
                    borderWidth: 0,
                    borderSkipped: false,
                    backgroundColor: "rgba(56, 189, 248, 0.75)",
                    borderColor: "rgba(56, 189, 248, 1)",
                    data: [450, 200, 100, 220, 500, 100, 400, 230, 500, 200],
                    maxBarThickness: 10
                },
                {
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderSkipped: 'bottom',
                    borderRadius: {
                        topLeft: 2,
                        topRight: 2,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    backgroundColor: "rgba(124, 58, 237, 0.75)",
                    borderColor: "rgba(124, 58, 237, 1)",
                    data: [200, 300, 200, 420, 400, 200, 300, 430, 400, 300],
                    maxBarThickness: 10
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e9ecef',
                    borderWidth: 1,
                    usePointStyle: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [4, 4],
                    },
                    ticks: {
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
                x: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
            },
        },
    });

    new Chart(surveys_ctx, {
        type: "bar",
        data: {
            labels: ["Ahmad", "Mohamad", "Zain", "Islam", "Yasser", "Ali", "Khaled", "Mona", "Nada", "Sarah"],
            datasets: [{
                    label: "Surveys",
                    tension: 0.8,
                    borderWidth: 0,
                    borderSkipped: 'bottom',
                    borderRadius: {
                        topLeft: 4,
                        topRight: 4,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    backgroundColor: 'rgba(56, 189, 248, 0.75)',
                    borderColor: 'rgba(56, 189, 248, 1)',
                    data: [450, 200, 100, 220, 500, 100, 400, 230, 500, 200],
                    maxBarThickness: 24
                }
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e9ecef',
                    borderWidth: 1,
                    usePointStyle: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [4, 4],
                    },
                    ticks: {
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
                x: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
            },
        },
    });

    new Chart(total_orders_ctx, {
        type: "bar",
        data: {
            labels: ["Reorders", "New Deals", "Surveys"],
            datasets: [{
                    label: "Total Orders",
                    tension: 0.8,
                    borderWidth: 0,
                    borderSkipped: 'bottom',
                    borderRadius: {
                        topLeft: 6,
                        topRight: 6,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    backgroundColor: 'rgba(56, 189, 248, 0.75)',
                    borderColor: 'rgba(56, 189, 248, 1)',
                    data: [monthlyIncome.total_reorders, monthlyIncome.total_new_orders, monthlyIncome.total_surveys],
                    maxBarThickness: 36
                }
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e9ecef',
                    borderWidth: 1,
                    usePointStyle: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [4, 4],
                    },
                    ticks: {
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
                x: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
            },
        },
    });

    new Chart(total_orders_by_status_ctx, {
        type: "bar",
        data: {
            labels: ["Pending", "Confirmed", "Shipped", "Completed", "Cancelled", "Suspended", "Returned"],
            datasets: [{
                    label: "Total Orders by Status",
                    tension: 0.8,
                    borderWidth: 0,
                    borderSkipped: 'bottom',
                    borderRadius: {
                        topLeft: 6,
                        topRight: 6,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.75)', // Pending - amber
                        'rgba(56, 189, 248, 0.75)', // Confirmed - sky blue
                        'rgba(251, 146, 60, 0.75)', // Shipped - orange
                        'rgba(34, 197, 94, 0.75)', // Completed - green
                        'rgba(244, 63, 94, 0.75)', // Cancelled - red
                        'rgba(148, 163, 184, 0.75)', // Suspended - gray
                        'rgba(139, 92, 246, 0.75)'  // Returned - purple
                    ],
                    borderColor: [
                        'rgba(245, 158, 11, 1)',
                        'rgba(56, 189, 248, 1)',
                        'rgba(251, 146, 60, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(244, 63, 94, 1)',
                        'rgba(148, 163, 184, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    data: [monthlyOrders['pending'] ?? 0, monthlyOrders['confirmed'] ?? 0, monthlyOrders['shipped'] ?? 0, monthlyOrders['completed'] ?? 0, monthlyOrders['cancelled'] ?? 0, monthlyOrders['suspended'] ?? 0, monthlyOrders['returned'] ?? 0],
                    maxBarThickness: 36
                }
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e9ecef',
                    borderWidth: 1,
                    usePointStyle: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [4, 4],
                    },
                    ticks: {
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
                x: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    },
                },
            },
        },
    });

    // Assuming `$yearly_income` is passed to the frontend as JSON
    const yearlyIncome = @json($yearly_income);

    // Extract labels (formatted months) and data (income)
    const labels = yearlyIncome.map(entry => {
        const date = new Date(entry.collect_date); // Assuming `month` is in a date-compatible format
        return date.toLocaleString('en-US', { year: 'numeric', month: 'short' }); // Format: YYYY-MMM
    });
    const data = yearlyIncome.map(entry => entry.income);

    var ctx = document.getElementById("chart-line").getContext("2d");

    var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

    gradientStroke.addColorStop(1, 'rgba(45,168,255,0.2)');
    gradientStroke.addColorStop(0.2, 'rgba(45,168,255,0.0)');
    gradientStroke.addColorStop(0, 'rgba(45,168,255,0)'); // Blue colors

    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels, // Dynamic labels (formatted months)
            datasets: [{
                label: "Monthly Income",
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 3,
                borderColor: "#2ca8ff",
                pointBorderColor: '#2ca8ff',
                pointBackgroundColor: '#2ca8ff',
                backgroundColor: gradientStroke,
                fill: true,
                data: data, // Dynamic income data
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 6,
                        boxHeight: 6,
                        padding: 20,
                        pointStyle: 'circle',
                        borderRadius: 50,
                        usePointStyle: true,
                        font: {
                            weight: 400,
                        },
                    },
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e9ecef',
                    borderWidth: 1,
                    pointRadius: 2,
                    usePointStyle: true,
                    boxWidth: 8,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [4, 4]
                    },
                    ticks: {
                        stepSize: 1000, // Increment by 1000
                        callback: function(value) {
                            return value.toLocaleString() + ' USD'; // Format with thousand separators
                        },
                        display: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B"
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [4, 4]
                    },
                    ticks: {
                        display: true,
                        padding: 20,
                        font: {
                            size: 12,
                            family: "Noto Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#64748B",
                        maxRotation: 45, // Tilt the labels
                        minRotation: 45
                    }
                },
            },
        },
    });

</script>
@stop