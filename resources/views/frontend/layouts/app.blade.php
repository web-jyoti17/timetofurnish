<!DOCTYPE html>

@php
    $rtl = get_session_language()->rtl;
@endphp

@if ($rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <title>@yield('meta_title', get_setting('website_name') . ' | ' . get_setting('site_motto'))</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description'))" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">

    @yield('meta')

    @if (!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
        @php
            $meta_image = uploaded_asset(get_setting('meta_image'));
        @endphp
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ get_setting('meta_title') }}">
        <meta itemprop="description" content="{{ get_setting('meta_description') }}">
        <meta itemprop="image" content="{{ $meta_image }}">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="{{ get_setting('meta_title') }}">
        <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
        <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ $meta_image }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ get_setting('meta_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:image" content="{{ $meta_image }}" />
    <meta property="og:description" content="{{ get_setting('meta_description') }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif

    <!-- Favicon -->
    @php
    $site_icon = uploaded_asset(get_setting('site_icon'));
    @endphp
    <link rel="icon" href="{{ $site_icon }}">
    <link rel="apple-touch-icon" href="{{ $site_icon }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    @if ($rtl == 1)
    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000, 9999) }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css?v=') }}{{ rand(1000, 9999) }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Ensure jQuery is loaded before select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
    </script>

    <style>
        :root {
            --blue: #3490f3;
            --hov-blue: #2e7fd6;
            --soft-blue: rgba(0, 123, 255, 0.15);

            --secondary-base: {{ get_setting('secondary_base_color', '#ffc519') }};
            --hov-secondary-base: {{ get_setting('secondary_base_hov_color', '#dbaa17') }};
            --soft-secondary-base: {{ hex2rgba(get_setting('secondary_base_color', '#ffc519'), 0.15) }};
            --gray: #9d9da6;
            --gray-dark: #8d8d8d;
            --secondary: #919199;
            --soft-secondary: rgba(145, 145, 153, 0.15);
            --success: #85b567;
            --soft-success: rgba(133, 181, 103, 0.15);
            --warning: #f3af3d;
            --soft-warning: rgba(243, 175, 61, 0.15);
            --light: #f5f5f5;
            --soft-light: #dfdfe6;
            --soft-white: #b5b5bf;
            --dark: #292933;
            --soft-dark: #1b1b28;
            --primary: {{ get_setting('base_color', '#d43533') }};
            --hov-primary: {{ get_setting('base_hov_color', '#9d1b1a') }};
            --soft-primary: {{ hex2rgba(get_setting('base_color', '#d43533'), 0.15) }};

            ;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
        }

        .addon-toggle-btn {
            background: #f5eee6;
            border: 1px solid #e2d2c0;
            color: #8b5e34;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            padding: 6px 12px;
            transition: all .3s ease;
        }

        .addon-toggle-btn:hover {
            background: #8b5e34;
            color: #fff;
        }

        .addon-toggle-btn:focus {
            box-shadow: none !important;
        }

        .custom_checkout_button_design {
            background: transparent !important;
            border: 1px solid #685b4e !important;
            border-radius: 5px !important;
            color: #fff !important;
        }

        .custom_checkout_button_design.filled {
            background: #685b4e;
        }

        .pagination .page-link,
        .page-item.disabled .page-link {
            min-width: 32px;
            min-height: 32px;
            line-height: 32px;
            text-align: center;
            padding: 0;
            border: 1px solid var(--soft-light);
            font-size: 0.875rem;
            border-radius: 0 !important;
            color: var(--dark);
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .aiz-carousel.coupon-slider .slick-track {
            margin-left: 0;
        }

        .form-control:focus {
            border-width: 2px !important;
        }

        .iti__flag-container {
            padding: 2px;
        }

        .modal-content {
            border: 0 !important;
            border-radius: 0 !important;
        }

        #map {
            width: 100%;
            height: 250px;
        }

        #edit_map {
            width: 100%;
            height: 250px;
        }

        .pac-container {
            z-index: 100000;
        }

        /* ----------------------------------------------------
           PREMIUM CUSTOM CHECKOUT & CART RESPONSIVE DESIGN STYLES
           ---------------------------------------------------- */
        /* Unify Checkout Steps to use warm-gold theme color (#b57a45) exclusively */
        .cart_tabs .col.active .border-bottom-6px,
        .cart_tabs .col.done .border-bottom-6px {
            border-bottom-color: #b57a45 !important;
            border-color: #b57a45 !important;
        }

        .cart_tabs .col.active .text-primary,
        .cart_tabs .col.done .text-success,
        .cart_tabs .col.active .text-primary a,
        .cart_tabs .col.done .text-success a {
            color: #b57a45 !important;
        }

        .cart_tabs .col.active svg path,
        .cart_tabs .col.done svg path,
        .cart_tabs .col.active svg [fill="#d43533"],
        .cart_tabs .col.done svg [fill="#d43533"],
        .cart_tabs .col.active svg g path,
        .cart_tabs .col.done svg g path {
            fill: #b57a45 !important;
        }

        /* Fix clipping or shifting checkout step icons on mobile */
        .cart-animate {
            margin: 0 auto !important;
            transform: none !important;
            float: none !important;
        }

        /* Premium, Minimalist Checkout Stepper (Soft Background Colors) */
        .cart_tabs {
            background: #ffffff !important;
            padding: 25px 0 15px 0 !important;
        }

        .cart_tabs .row.gutters-5 {
            border-bottom: 0 !important; /* Remove baseline line */
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
        }

        .cart_tabs .col .text-center {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 85px !important;
            border-radius: 16px !important; /* Premium rounded cards */
            background: #faf8f5 !important; /* Default soft warm-gray background */
            border: none !important; /* Eliminate hard borders completely */
            box-shadow: none !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            padding: 12px 18px !important;
            position: relative !important;
        }

        .cart_tabs .col .text-center::after {
            display: none !important; /* Remove baseline underlines */
        }

        /* Hide the old-fashioned heavy 6px bottom border completely */
        .cart_tabs .col .border-bottom-6px,
        .cart_tabs .col.active .border-bottom-6px,
        .cart_tabs .col.done .border-bottom-6px {
            border-bottom: 0 !important;
            border-bottom-width: 0 !important;
        }

        /* Active tab: Soft warm pastel gold background */
        .cart_tabs .col.active .text-center {
            background: #fdf2e3 !important; /* Soft warm-gold pastel */
            border: none !important;
            box-shadow: 0 6px 20px rgba(181, 122, 69, 0.08) !important;
            transform: translateY(-2px) !important;
        }

        .cart_tabs .col.active .text-center i {
            color: #b57a45 !important;
            font-size: 2.2rem !important;
            opacity: 1 !important;
            transform: scale(1.05) !important;
            transition: all 0.25s ease !important;
        }

        .cart_tabs .col.active .text-center h3,
        .cart_tabs .col.active .text-center a {
            color: #b57a45 !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            margin-top: 8px !important;
            letter-spacing: 0.3px !important;
        }

        /* Done/Completed tab: Soft muted pastel green background */
        .cart_tabs .col.done .text-center {
            background: #f1f8f3 !important; /* Soft pastel green indicating completion */
            border: none !important;
            box-shadow: none !important;
            opacity: 1 !important;
        }

        .cart_tabs .col.done .text-center i {
            color: #4e824e !important; /* Soft green icon */
            font-size: 2rem !important;
        }

        .cart_tabs .col.done .text-center h3,
        .cart_tabs .col.done .text-center a {
            color: #4e824e !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            margin-top: 8px !important;
        }

        /* Inactive/Pending tabs: Soft warm off-white background */
        .cart_tabs .col:not(.active):not(.done) .text-center {
            background: #faf8f5 !important; /* Soft off-white */
            border: none !important;
            opacity: 0.65 !important;
        }

        .cart_tabs .col:not(.active):not(.done) .text-center i {
            color: #a59f96 !important; /* Soft gray icon */
            font-size: 2rem !important;
        }

        .cart_tabs .col:not(.active):not(.done) .text-center h3,
        .cart_tabs .col:not(.active):not(.done) .text-center a {
            color: #a59f96 !important;
            font-weight: 500 !important;
            font-size: 14px !important;
            margin-top: 8px !important;
        }

        .cart_tabs .col:not(.active):not(.done) .text-center:hover {
            opacity: 0.9 !important;
            background: #f5f2eb !important; /* Slightly warmer gray on hover */
        }

        /* Sleek horizontal timeline connector running exactly behind the step cards on desktop */
        @media (min-width: 992px) {
            .cart_tabs .row.gutters-5 {
                position: relative;
                z-index: 1;
            }
            .cart_tabs .row.gutters-5::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 10%;
                right: 10%;
                height: 2px;
                background: #f2ebe1; /* Very light timeline connection line */
                z-index: 0;
                transform: translateY(-50%);
            }
            .cart_tabs .col {
                z-index: 2;
                position: relative;
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }

        /* Align and prevent mobile overflow for Variation & Addon Selected Tables */
        .addon-details {
            width: 100% !important;
            padding: 0 !important;
        }

        /* Premium, Ultra-Modern Table Styling for Checkout Addons / Variations */
        .addon-details table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border-radius: 12px !important;
            border: 1px solid #f0e6da !important;
            background: #ffffff !important;
            overflow: hidden !important;
            box-shadow: 0 4px 12px rgba(181, 122, 69, 0.02) !important;
            margin-bottom: 15px !important;
            table-layout: fixed !important;
        }

        .addon-details table th,
        .addon-details table td {
            word-break: break-word !important;
            white-space: normal !important;
        }

        .addon-details table th.addon-header,
        .addon-details table th {
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            background: #faf8f5 !important;
            color: #8b7a6b !important;
            font-weight: 700 !important;
            padding: 12px 18px !important;
            border-bottom: 1px solid #f0e6da !important;
            border-top: none !important;
        }

        .addon-details table td {
            font-size: 13px !important;
            color: #3e3327 !important;
            font-weight: 500 !important;
            padding: 14px 18px !important;
            background: #ffffff !important;
            border-bottom: 1px solid #f8f5f0 !important;
            border-top: none !important;
        }

        /* Last row td should not have bottom border */
        .addon-details table tr:last-child td {
            border-bottom: none !important;
        }

        .addon-details table td .addon-separator {
            color: #d1c6b8 !important;
            margin: 0 8px !important;
        }

        /* Sleek custom modern pill quantity selector */
        .modern-qty-selector {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            background: #faf8f5 !important;
            border: 1px solid #e5dec9 !important;
            border-radius: 8px !important;
            height: 38px !important;
            width: 110px !important;
            overflow: hidden !important;
            padding: 2px !important;
        }

        .modern-qty-selector .qty-btn {
            border: none !important;
            background: transparent !important;
            color: #b57a45 !important;
            width: 32px !important;
            height: 32px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .modern-qty-selector .qty-btn:hover:not(:disabled) {
            background: #f1e9dc !important;
            color: #8b5a30 !important;
        }

        .modern-qty-selector .qty-btn:disabled {
            color: #ccc !important;
            cursor: not-allowed !important;
            background: transparent !important;
        }

        .modern-qty-selector .qty-input {
            border: none !important;
            background: transparent !important;
            color: #2e241c !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            text-align: center !important;
            width: 38px !important;
            height: 32px !important;
            padding: 0 !important;
            margin: 0 !important;
            outline: none !important;
            pointer-events: none !important; /* Force button click controls */
            box-shadow: none !important;
        }

        /* Disable default arrows in number inputs */
        .modern-qty-selector input[type=number]::-webkit-inner-spin-button, 
        .modern-qty-selector input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none;
            margin: 0; 
        }

        /* Map text-success inside checkout tables and totals to warm-gold theme color */
        .addon-details table td .text-success,
        .addon-details table td.text-success {
            color: #b57a45 !important;
            font-weight: 600 !important;
        }

        /* Force checkout buttons to use our theme color exclusively */
        .custom_checkout_button_design.filled {
            background: #b57a45 !important;
            border-color: #b57a45 !important;
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(181, 122, 69, 0.15) !important;
            transition: all 0.25s ease !important;
        }

        .custom_checkout_button_design.filled:hover {
            background: #8b5a30 !important;
            border-color: #8b5a30 !important;
            color: #fff !important;
            transform: translateY(-1.5px) !important;
        }

        .custom_checkout_button_design.unfilled {
            background: transparent !important;
            border: 2px solid #b57a45 !important;
            color: #b57a45 !important;
            transition: all 0.25s ease !important;
        }

        .custom_checkout_button_design.unfilled:hover {
            background: #b57a45 !important;
            color: #fff !important;
            border-color: #b57a45 !important;
            transform: translateY(-1.5px) !important;
        }

        /* General layout adjustments for mobile screens */
        @media (max-width: 575.98px) {
            .cart_tabs {
                padding: 15px 0 5px 0 !important;
            }
            .cart_tabs .col {
                padding-left: 4px !important;
                padding-right: 4px !important;
            }
            .cart_tabs .col .text-center {
                min-height: 70px !important;
                border-radius: 12px !important;
                padding: 8px !important;
            }
            .cart_tabs .col .text-center i {
                font-size: 1.6rem !important;
                margin-bottom: 0 !important;
            }
        }
    </style>

    @if (get_setting('google_analytics') == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '{{ env('TRACKING_ID') }}');
    </script>
    @endif


</head>

<body>
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column bg-white iuygh">
        @php
        $user = auth()->user();
        $user_avatar = null;
        $carts = [];
        if ($user && $user->avatar_original != null) {
        $user_avatar = uploaded_asset($user->avatar_original);
        }

        $system_language = get_system_language();

        @endphp
        <!-- Header -->
        @include('frontend.inc.nav')

        @yield('content')

        <!-- footer -->
        @include('frontend.inc.footer')

    </div>

    @if (env('DEMO_MODE') == 'On')
    <!-- demo nav -->
    @include('frontend.inc.demo_nav')
    @endif

    <!-- cookies agreement -->
    @if (get_setting('show_cookies_agreement') == 'on')
    <div class="aiz-cookie-alert shadow-xl">
        <div class="p-3 bg-dark rounded">
            <div class="text-white mb-3">
                @php
                echo get_setting('cookies_agreement_text');
                @endphp
            </div>
            <button class="btn btn-primary aiz-cookie-accept">
                {{ translate('Ok. I Understood') }}
            </button>
        </div>
    </div>
    @endif

    <!-- website popup -->
    @if (get_setting('show_website_popup') == 'on')
    <div class="modal website-popup removable-session d-none" data-key="website-popup" data-value="removed">
        <div class="absolute-full bg-black opacity-60"></div>
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-md mx-4 mx-md-auto">
            <div class="modal-content position-relative border-0 rounded-0">
                <div class="aiz-editor-data">
                    {!! get_setting('website_popup_content') !!}
                </div>
                @if (get_setting('show_subscribe_form') == 'on')
                <div class="pb-5 pt-4 px-3 px-md-5">
                    <form class="" method="POST" action="{{ route('subscribers.store') }}">
                        @csrf
                        <div class="form-group mb-0">
                            <input type="email" class="form-control" placeholder="{{ translate('Your Email Address') }}" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3">
                            {{ translate('Subscribe Now') }}
                        </button>
                    </form>
                </div>
                @endif
                <button class="absolute-top-right bg-white shadow-lg btn btn-circle btn-icon mr-n3 mt-n3 set-session" data-key="website-popup" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                    <i class="la la-close fs-20"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    @include('frontend.' . get_setting('homepage_select') . '.partials.modal')

    @include('frontend.' . get_setting('homepage_select') . '.partials.account_delete_modal')

    <div class="modal fade" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader text-center p-3">
                    <i class="las la-spinner la-spin la-3x"></i>
                </div>
                <button type="button" class="close absolute-top-right btn-icon close z-1 btn-circle bg-gray mr-2 mt-2 d-flex justify-content-center align-items-center" data-dismiss="modal" aria-label="Close" style="background: #ededf2; width: calc(2rem + 2px); height: calc(2rem + 2px);">
                    <span aria-hidden="true" class="fs-24 fw-700" style="margin-left: 2px;">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>

    @yield('modal')
    <x-cookie-banner />

    <style>
        @media only screen and (max-width: 991px) {

            body,
            html {
                overflow-x: hidden !important;
            }
        }

        @media only screen and (max-width: 1500px) {
            .aiz-carousel .slick-next {
                right: 0;
            }

            .aiz-carousel .slick-prev {
                left: 0;
            }

            .aiz-carousel .slick-arrow {
                top: 50% !important;
                background: #ffffff87 !important;
            }
        }

        @media only screen and (max-width: 767px) {
            .aiz-carousel .slick-next {
                right: -15px;
            }

            .aiz-carousel .slick-prev {
                left: -15px;
            }

            .aiz-carousel .slick-arrow {
                top: 10%;
                background: transparent;
            }
        }
    </style>
    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000, 9999) }}"></script>



    @if (get_setting('facebook_chat') == 1)
    <script type="text/javascript">
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v3.3'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div id="fb-root"></div>
    <!-- Your customer chat code -->
    <div class="fb-customerchat"
        attribution=setup_tool
        page_id="{{ env('FACEBOOK_PAGE_ID') }}">
    </div>
    @endif

    <script>
        @foreach (session('flash_notification', collect())->toArray() as $message)
        AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach
    </script>

    <script>
        @if (Route::currentRouteName() == 'home' || Route::currentRouteName() == '/')

        $.post('{{ route('home.section.featured') }}', { _token: '{{ csrf_token() }}'},
            function(data) {
                if ($.trim(data)) {
                    console.log(data);
                    $('#section_featured').html(data).show();
                    AIZ.plugins.slickCarousel();
                } else {
                    $('#section_featured').hide();
                }
            });


        $.post('{{ route('home.section.todays_deal') }}', {
                _token: '{{ csrf_token() }}'
            },
            function(data) {
                $('#todays_deal').html(data);
                AIZ.plugins.slickCarousel();
            });

        $.post('{{ route('home.section.best_selling') }}', {
                _token: '{{ csrf_token() }}'
            },
            function(data) {
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });

        $.post('{{ route('home.section.newest_products') }}', {
                _token: '{{ csrf_token() }}'
            },
            function(data) {
                $('#section_newest').html(data);
                AIZ.plugins.slickCarousel();
            });

        $.post('{{ route('home.section.auction_products') }}', {
                _token: '{{ csrf_token() }}'
            },
            function(data) {
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });

        $.post('{{ route('home.section.home_categories') }}', {
                _token: '{{ csrf_token() }}'
            },
            function(data) {
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });
        @endif

        $(document).ready(function() {
            $('.category-nav-element').each(function(i, el) {

                $(el).on('mouseover', function() {
                    if (!$(el).find('.sub-cat-menu').hasClass('loaded')) {
                        $.post('{{ route('category.elements') }}', {
                                _token: AIZ.data.csrf,
                                id: $(el).data('id')
                            },
                            function(data) {
                                $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                            });
                    }
                });
            });

            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        $.post('{{ route('language.change') }}', {
                                _token: AIZ.data.csrf,
                                locale: locale
                            },
                            function(data) {
                                location.reload();
                            });

                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();
                        var $this = $(this);
                        var currency_code = $this.data('currency');
                        $.post('{{ route('currency.change') }}', {
                                _token: AIZ.data.csrf,
                                currency_code: currency_code
                            },
                            function(data) {
                                location.reload();
                            });

                    });
                });
            }
        });

        $('#search').on('keyup', function() {
            search();
        });

        $('#search').on('focus', function() {
            search();
        });

        function search() {
            var searchKey = $('#search').val();
            if (searchKey.length > 0) {
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', {
                        _token: AIZ.data.csrf,
                        search: searchKey
                    },
                    function(data) {
                        if (data == '0') {
                            // $('.typed-search-box').addClass('d-none');
                            $('#search-content').html(null);
                            $('.typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"' + searchKey + '"</strong>');
                            $('.search-preloader').addClass('d-none');

                        } else {
                            $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                            $('#search-content').html(data);
                            $('.search-preloader').addClass('d-none');
                        }
                    });
            } else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }


        $('#search_mobile').on('keyup', function() {
            searchMobile();
        });

        $('#search_mobile').on('focus', function() {
            searchMobile();
        });

        function searchMobile() {
            var searchKey = $('#search_mobile').val();
            if (searchKey.length > 0) {
                $('body').addClass("typed-search-box-shown");

                $('.mobile_search .typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', {
                        _token: AIZ.data.csrf,
                        search: searchKey
                    },
                    function(data) {
                        if (data == '0') {
                            // $('.typed-search-box').addClass('d-none');
                            $('#search-content_mobile').html(null);
                            $('.mobile_search .typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"' + searchKey + '"</strong>');
                            $('.search-preloader').addClass('d-none');

                        } else {
                            $('.mobile_search .typed-search-box .search-nothing').addClass('d-none').html(null);
                            $('#search-content_mobile').html(data);
                            $('.search-preloader').addClass('d-none');
                        }
                    });
            } else {
                $('.mobile_search .typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        $(".aiz-user-top-menu").on("mouseover", function(event) {
                $(".hover-user-top-menu").addClass('active');
            })
            .on("mouseout", function(event) {
                $(".hover-user-top-menu").removeClass('active');
            });

        $(document).on("click", function(event) {
            var $trigger = $("#category-menu-bar");
            if ($trigger !== event.target && !$trigger.has(event.target).length) {
                $("#click-category-menu").slideUp("fast");;
                $("#category-menu-bar-icon").removeClass('show');
            }
        });

        function updateNavCart(view, count) {
            $('.cart-count').html(count);
            $('#cart_items').html(view);
        }

        function removeFromCart(key) {
            $.post('{{ route('cart.removeFromCart') }}', {
                    _token: AIZ.data.csrf,
                    id: key
                },
                function(data) {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                    $('#cart-summary').html(data.cart_view);
                    AIZ.plugins.notify('success', "{{ translate('Item has been removed from cart') }}");
                    $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html()) - 1);
                });
        }

        function showLoginModal() {
            if ($('#option-choice-form').length && $('#option-choice-form input[name="id"]').length) {
                $.post('{{ route('cart.savePendingSelection') }}', $('#option-choice-form').serializeArray())
                    .always(function() {
                        $('#login_modal').modal();
                    });

                return;
            }

            $('#login_modal').modal();
        }

        function addToCompare(id) {
            $.post('{{ route('compare.addToCompare') }}', {
                    _token: AIZ.data.csrf,
                    id: id
                },
                function(data) {
                    $('#compare').html(data);
                    AIZ.plugins.notify('success', "{{ translate('Item has been added to compare list') }}");
                    $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html()) + 1);
                });
        }

        function addToWishList(id) {
            @if (Auth::check() && Auth::user()->user_type == 'customer')
            $.post('{{ route('wishlists.store') }}', {
                    _token: AIZ.data.csrf,
                    id: id
                },
                function(data) {
                    if (data != 0) {
                        $('#wishlist').html(data);
                        AIZ.plugins.notify('success', "{{ translate('Item has been added to wishlist') }}");
                    } else {
                        AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                    }
                });
            @elseif(Auth::check() && Auth::user()->user_type != 'customer')
            AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the WishList.') }}");
            @else
            AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
            @endif
        }

        function showAddToCartModal(id) {
            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.post('{{ route('cart.showCartModal') }}', {
                    _token: AIZ.data.csrf,
                    id: id
                },
                function(data) {
                    $('.c-preloader').hide();
                    $('#addToCart-modal-body').html(data);
                    AIZ.plugins.slickCarousel();
                    AIZ.plugins.zoom();
                    AIZ.extra.plusMinus();
                    getVariantPrice();
                });
        }

        $('#option-choice-form input').on('change', function() {

            let name = $(this).attr('name');

            // If it's checkbox group → allow only one checked
            if ($(this).attr('type') === 'checkbox') {
                $('input[name="' + name + '"]').not(this).prop('checked', false);
            }

            // Recalculate price
            getVariantPrice();
        });


        // Enforce single selection per addon group

        function getVariantPrice() {

            let qty = parseInt($("#quantity").val()) || 1;

            if (qty <= 0) return;

            $.ajax({
                type: "POST",
                url: '{{ route('products.variant_price') }}',
                data: $('#option-choice-form').serializeArray(),

                success: function(data) {

                    /*
                    Backend already returns variant price
                    DO NOT add attribute price again
                    */

                    let base_price = parseFloat(
                        data.price.toString()
                        .replace(/,/g, '')
                        .replace(/[^0-9.]/g, '')
                    ) || 0;

                    let addon_total = 0;
                    let variant_ready = true;
                    let has_addon_selection = false;

                    $('#option-choice-form .variant-dropdown')
                        .each(function() {
                            if (!$(this).val()) {
                                variant_ready = false;
                            }
                        });

                    /*
                    ONLY ADD ADDONS
                    EXCLUDE variant-dropdown
                    */

                    $('#option-choice-form .addon-block .custom-dropdown')
                        .each(function() {
                            if ($(this).hasClass('fabric-dropdown')) return;

                            let selected =
                                $(this).find('option:selected');

                            if (!selected.val()) return;

                            has_addon_selection = true;

                            let price =
                                parseFloat(
                                    selected.data('price')
                                ) || 0;

                            addon_total += price;
                        });


                    /*
                    FABRIC
                    */

                    $('input[type="hidden"][name^="addons"]')
                        .each(function() {

                            let optionId = $(this).val();

                            if (!optionId) return;

                            has_addon_selection = true;

                            let fabricBtn =
                                $('.fabric-color-box[data-optionid="' + optionId + '"]');

                            if (fabricBtn.length) {

                                let fabricPrice =
                                    parseFloat(
                                        fabricBtn.data('price')
                                    ) || 0;

                                addon_total += fabricPrice;
                            }

                        });

                    // Calculate selected attributes price on frontend
                    let selected_attributes_price = 0;
                    $('#option-choice-form .variant-dropdown').each(function() {
                        let selected = $(this).find('option:selected');
                        if (selected.val()) {
                            let price = parseFloat(selected.data('price')) || 0;
                            selected_attributes_price += price;
                        }
                    });

                    if (!variant_ready) {
                        let original_actual = parseFloat($('.js-product-base-price').first().attr('data-original-actual-base-price'));
                        if (isNaN(original_actual)) {
                            original_actual = parseFloat($('.js-product-base-price').first().data('actual-base-price')) || 0;
                            $('.js-product-base-price').first().attr('data-original-actual-base-price', original_actual);
                        }
                        base_price = original_actual + selected_attributes_price;
                    } else {
                        if (base_price > 0) {
                            base_price = base_price / qty;
                        }
                    }

                    // Dynamically update the .js-product-base-price elements
                    $('.js-product-base-price').each(function() {
                        $(this).html(base_price.toFixed(2));
                        $(this).attr('data-base-price', base_price.toFixed(2));
                        $(this).attr('data-actual-base-price', base_price);
                    });

                    /*
                    CHECK IF ANY ATTRIBUTE SELECTED
                    */
                    let has_variant_selection = false;

                    $('#option-choice-form .variant-dropdown').each(function () {
                        if ($(this).val()) {
                            has_variant_selection = true;
                        }
                    });


                    /*
                    SHOW DEFAULT BASE PRICE UNTIL USER SELECTS
                    */

                    if (!has_variant_selection && !has_addon_selection) {
                        let default_price_text = $('.js-product-total-price').first().data('default-price-text');
                        if (!default_price_text) {
                            let basePriceVal = parseFloat($('.js-product-base-price').data('base-price')) || 0;
                            default_price_text = '£ ' + basePriceVal.toFixed(2);
                        }

                        $('.js-product-total-price').html(default_price_text);

                        $('#chosen_price').html(default_price_text);

                        $('#chosen_price_div').addClass('d-none');
                        $('#total-price-div').addClass('d-none');

                        return;

                    } else {

                        $('#chosen_price_div').removeClass('d-none');
                        $('#total-price-div').removeClass('d-none');
                    }


                    /*
                    QTY
                    */

                    let final_price =
                        (base_price + addon_total) *
                        qty;


                    /*
                    UPDATE UI
                    */

                    $('.js-product-total-price')
                        .html(
                            '£ ' + final_price.toFixed(2)
                        );


                    $('#chosen_price_div')
                        .removeClass('d-none');

                    $('#chosen_price')
                        .html(
                            '£ ' + final_price.toFixed(2)
                        );


                    /*
                    STOCK
                    */

                    if (data.numeric_qty !== undefined) {
                        $('.available-amount').html('(<span id="available-quantity" style="font-weight:600;">' + data.numeric_qty + '</span> ' + '{{ translate("available") }}' + ')');
                    } else {
                        $('#available-quantity')
                            .html(data.quantity);
                    }

                    $('.input-number')
                        .prop(
                            'max',
                            data.max_limit
                        );


                    if (
                        parseInt(data.in_stock) === 0 &&
                        data.digital === 0
                    ) {

                        // $('.buy-now,.add-to-cart')
                        //     .addClass('d-none');

                        $('.out-of-stock')
                            .removeClass('d-none');

                    } else {

                        // $('.buy-now,.add-to-cart')
                        //     .removeClass('d-none');

                        $('.out-of-stock')
                            .addClass('d-none');
                    }

                    AIZ.extra.plusMinus();
                }
            });
        }

        function checkAddToCartValidity() {

            let valid = true;

            $('#option-choice-form .custom-dropdown').each(function() {

                let value = $(this).val();

                if (!value || value.length === 0) {
                    valid = false;
                }

            });

            return valid;
        }

        $(document).on('change', '.custom-dropdown', function() {

            getVariantPrice();

            // Preview update
            let selected = $(this).find('option:selected');
            let addonId = $(this).attr('name').match(/\d+/)[0];
            let previewBox = $('#addon-preview-' + addonId);

            if (selected.data('img')) {
                previewBox.html(`
                    <div class="fabric-box active-preview">
                        <img src="${selected.data('img')}" style="width:60px;height:60px;object-fit:cover;">
                    </div>
                `);
            } else {
                previewBox.html(`
                    <div class="fabric-box no-image active-preview">
                        <span>${selected.data('name')}</span>
                    </div>
                `);
            }

        });

        function addToCart() {
            @if (Auth::check() && Auth::user()->user_type != 'customer')
            AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
            return false;
            @endif
            let qty = parseInt($("#quantity").val());
            let qty1 = parseInt($("#qty1").val());
            if (qty > qty1) {
                AIZ.plugins.notify('warning', "{{ translate('Please Check Quantity.') }}");
                return false;
            }
            if (checkAddToCartValidity()) {
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                    type: "POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data) {
                        $('#addToCart-modal-body').html(null);
                        $('.c-preloader').hide();
                        $('#modal-size').removeClass('modal-lg');
                        $('#addToCart-modal-body').html(data.modal_view);
                        AIZ.extra.plusMinus();
                        AIZ.plugins.slickCarousel();
                        updateNavCart(data.nav_cart_view, data.cart_count);
                    }
                });
            } else {
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function buyNow() {
            @if (Auth::check() && Auth::user()->user_type != 'customer')
            AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
            return false;
            @endif
            let qty = parseInt($("#quantity").val());
            let qty1 = parseInt($("#qty1").val());
            if (qty > qty1) {
                AIZ.plugins.notify('warning', "{{ translate('Please Check Quantity.') }}");
                return false;
            }
            if (checkAddToCartValidity()) {
                $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                    type: "POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data) {
                        if (data.status == 1) {
                            $('#addToCart-modal-body').html(data.modal_view);
                            updateNavCart(data.nav_cart_view, data.cart_count);
                            window.location.replace("{{ route('cart') }}");
                        } else {
                            $('#addToCart-modal-body').html(null);
                            $('.c-preloader').hide();
                            $('#modal-size').removeClass('modal-lg');
                            $('#addToCart-modal-body').html(data.modal_view);
                        }
                    }
                });
            } else {
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function bid_single_modal(bid_product_id, min_bid_amount) {
            @if (Auth::check() && (isCustomer() || isSeller()))
            var min_bid_amount_text = "({{ translate('Min Bid Amount: ') }}" + min_bid_amount + ")";
            $('#min_bid_amount').text(min_bid_amount_text);
            $('#bid_product_id').val(bid_product_id);
            $('#bid_amount').attr('min', min_bid_amount);
            $('#bid_for_product').modal('show');
            @elseif(Auth::check() && isAdmin())
            AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
            $('#login_modal').modal('show');
            @endif
        }

        function clickToSlide(btn, id) {
            $('#' + id + ' .aiz-carousel').find('.' + btn).trigger('click');
            $('#' + id + ' .slide-arrow').removeClass('link-disable');
            var arrow = btn == 'slick-prev' ? 'arrow-prev' : 'arrow-next';
            if ($('#' + id + ' .aiz-carousel').find('.' + btn).hasClass('slick-disabled')) {
                $('#' + id).find('.' + arrow).addClass('link-disable');
            }
        }

        function goToView(params) {
            document.getElementById(params).scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }

        function copyCouponCode(code) {
            navigator.clipboard.writeText(code);
            AIZ.plugins.notify('success', "{{ translate('Coupon Code Copied') }}");
        }

        $(document).ready(function() {
            $('.cart-animate').animate({
                margin: 0
            }, "slow");

            $({
                deg: 0
            }).animate({
                deg: 360
            }, {
                duration: 2000,
                step: function(now) {
                    $('.cart-rotate').css({
                        transform: 'rotate(' + now + 'deg)'
                    });
                }
            });

            setTimeout(function() {
                $('.cart-ok').css({
                    fill: '#d43533'
                });
            }, 2000);

        });
    </script>

    @if (addon_is_activated('otp_system'))
    <script type="text/javascript">
        // Country Code
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if (country.iso2 == 'bd') {
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo get_active_countries()->pluck('code') @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if (selectedCountryData.iso2 == 'bd') {
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            // var currentMask = e.currentTarget.placeholder;
            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el) {
            if (isPhoneShown) {
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('*{{ translate('Use Phone Instead') }}');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('<i>*{{ translate('Use Email Instead') }}</i>');
            }
        }
    </script> @endif

    <script>
        var acc = document.getElementsByClassName("aiz-accordion-heading");
        var i;
        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
    </script>

    @if (env('DEMO_MODE') == 'On')
    <script>
        var demoNav = document.querySelector('.aiz-demo-nav');
        var menuBtn = document.querySelector('.aiz-demo-nav-toggler');
        var lineOne = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--1');
        var lineTwo = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--2');
        var lineThree = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--3');
        menuBtn.addEventListener('click', () => {
            toggleDemoNav();
        });

        function toggleDemoNav() {
            // demoNav.classList.toggle('show');
            demoNav.classList.toggle('shadow-none');
            lineOne.classList.toggle('line-cross');
            lineTwo.classList.toggle('line-fade-out');
            lineThree.classList.toggle('line-cross');
            if ($('.aiz-demo-nav-toggler').hasClass('show')) {
                $('.aiz-demo-nav-toggler').removeClass('show');
                demoHideOverlay();
            } else {
                $('.aiz-demo-nav-toggler').addClass('show');
                demoShowOverlay();
            }
        }

        $('.aiz-demos').click(function(e) {
            if (!e.target.closest('.aiz-demos .aiz-demo-content')) {
                toggleDemoNav();
            }
        });

        function demoShowOverlay() {
            $('.top-banner').removeClass('z-1035').addClass('z-1');
            $('.top-navbar').removeClass('z-1035').addClass('z-1');
            $('header').removeClass('z-1020').addClass('z-1');
            $('.aiz-demos').addClass('show');
        }

        function demoHideOverlay(cls = null) {
            if ($('.aiz-demos').hasClass('show')) {
                $('.aiz-demos').removeClass('show');
                $('.top-banner').delay(800).removeClass('z-1').addClass('z-1035');
                $('.top-navbar').delay(800).removeClass('z-1').addClass('z-1035');
                $('header').delay(800).removeClass('z-1').addClass('z-1020');
            }
        }
    </script> @endif
    <script>
        const searchInput = document.getElementById('search');
        const clearIcon = document.querySelector('.clear-search-icon');

        clearIcon.addEventListener('click', () => {
            searchInput.value = '';
        });
        $(".btn-addr").click(function() {
            $('input[name="address_id"]').prop("checked", false);
            $(this).siblings('input[type="radio"]').prop("checked", true);
        });
    </script>
    @yield('script')

    @php
        echo get_setting('footer_script');
    @endphp

</body>

</html>
