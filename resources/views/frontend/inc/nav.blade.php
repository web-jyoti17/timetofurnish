@php
    use Illuminate\Support\Facades\Cache;
    use App\Models\Category;
@endphp
<style>
    .clear-search-icon {
        position: absolute !important;
        top: 47% !important;
        right: 46px !important;
        transform: translateY(-50%);
        opacity: 0;
        cursor: pointer;
    }

    .banner-category.custom-banner-category .slick-track {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-input-box:hover .clear-search-icon,
    .search-input-box.focus .clear-search-icon {
        opacity: 1;
    }

    .test {
        background: #dacbbc;
        padding: 15px 0px 7px;
        letter-spacing: 1px;
    }
</style>
{{-- <marquee class="test">  <h6>  The website is currently in testing mode. Thank you for your patience.</h1></marquee> --}}

<!-- Top Bar Banner -->
@php
    $topbar_banner = get_setting('topbar_banner');
    $topbar_banner_medium = get_setting('topbar_banner_medium');
    $topbar_banner_small = get_setting('topbar_banner_small');
    $topbar_banner_asset = uploaded_asset($topbar_banner);
@endphp
@if ($topbar_banner != null)
    <div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner" data-value="removed">
        <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset h-40px h-lg-60px">
            <!-- For Large device -->
            <img src="{{ $topbar_banner_asset }}" class="d-none d-xl-block img-fit h-100"
                alt="{{ translate('topbar_banner') }}">
            <!-- For Medium device -->
            <img src="{{ $topbar_banner_medium != null ? uploaded_asset($topbar_banner_medium) : $topbar_banner_asset }}"
                class="d-none d-md-block d-xl-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
            <!-- For Small device -->
            <img src="{{ $topbar_banner_small != null ? uploaded_asset($topbar_banner_small) : $topbar_banner_asset }}"
                class="d-md-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
        </a>
        <button class="btn text-white h-100 absolute-top-right set-session" data-key="top-banner" data-value="removed"
            data-toggle="remove-parent" data-parent=".top-banner">
            <i class="la la-close la-2x"></i>
        </button>
    </div>
@endif

<!-- Top Bar -->


<header class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 bg-white custom-header-class">
    <!-- Search Bar -->
    <div class="position-relative logo-bar-area py-2 border-bottom border-md-nonea z-1025">
        <div class="container">
            <div class="d-flex align-items-center">
                <!-- top menu sidebar button -->
                <button type="button" class="btnNav d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                    data-target=".aiz-top-menu-sidebar">
                    <svg id="Component_43_1" data-name="Component 43 – 1" xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" viewBox="0 0 16 16">
                        <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                            transform="translate(0 7)" fill="#919199" />
                        <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2"
                            fill="#919199" />
                        <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                            transform="translate(0 14)" fill="#919199" />
                    </svg>

                </button>
                <!-- Header Logo -->
                <div class="col-auto pl-0 pr-3 d-flex align-items-center">
                    <a class="d-block py-2  mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-40px" height="40">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-40px" height="40">
                        @endif
                    </a>
                </div>
                <!-- Search Icon for small device -->
                <div class="d-lg-none small ml-auto mr-0">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>
                <div class="mobile_search front-header-search flex-grow-1 px-3 px-lg-0">
                    <form action="{{ route('search') }}" method="GET" class="stop-propagation ">
                        <div class="d-flex position-relative align-items-center">
                            <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                <button class="btn px-2" type="button"><i
                                        class="la la-2x la-long-arrow-left"></i></button>
                            </div>
                            <div class="search-input-box">
                                <input type="text"
                                    class="border border-soft-light form-control fs-14 hov-animate-outline"
                                    id="search_mobile" name="keyword"
                                    @isset($query)
                                            value="{{ $query }}"
                                        @endisset
                                    placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                    width="20.001" height="20" viewBox="0 0 20.001 20">
                                    <path id="Path_3090" data-name="Path 3090"
                                        d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                        transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                    <path id="Path_3091" data-name="Path 3091"
                                        d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                        transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                </svg>
                            </div>
                        </div>
                    </form>
                    <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                        style="min-height: 200px">
                        <div class="search-preloader absolute-top-center">
                            <div class="dot-loader">
                                <div>1</div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <div class="search-nothing d-none p-3 text-center fs-16">

                        </div>
                        <div id="search-content_mobile" class="text-left">

                        </div>
                    </div>
                </div>
                <!-- Search field -->
                <div class="flex-grow-1  d-flex align-items-center main_menu ">
                    @php
                        $nav_txt_color =
                            get_setting('header_nav_menu_text') == 'light' ||
                            get_setting('header_nav_menu_text') == null
                                ? 'text-white'
                                : 'text-dark';
                    @endphp
                    <div class="ml-xl-4 w-100  header_menu">
                        <div class="d-flex align-items-center h-100 justify-content-center">
                            <form action="{{ route('search') }}" method="GET" class="stop-propagation w-500px">
                                <div class="justify-content-center d-flex position-relative align-items-center">
                                    <div class="d-lg-none" data-toggle="class-toggle"
                                        data-target=".front-header-search">
                                        <button class="btn px-2" type="button"><i
                                                class="la la-2x la-long-arrow-left"></i></button>
                                    </div>
                                    <div class="search-input-box">
                                        <input type="text"
                                            class="border border-soft-light form-control fs-14 hov-animate-outline"
                                            id="search" name="keyword"
                                            @isset($query)
                                                value="{{ $query }}"
                                            @endisset
                                            placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                        <svg class="clear-search-icon" fill="#000000" width="20" height="20"
                                            viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M 7.21875 5.78125 L 5.78125 7.21875 L 14.5625 16 L 5.78125 24.78125 L 7.21875 26.21875 L 16 17.4375 L 24.78125 26.21875 L 26.21875 24.78125 L 17.4375 16 L 26.21875 7.21875 L 24.78125 5.78125 L 16 14.5625 Z" />
                                        </svg>
                                        <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                            width="20.001" height="20" viewBox="0 0 20.001 20">
                                            <path id="Path_3090" data-name="Path 3090"
                                                d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                                transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                            <path id="Path_3091" data-name="Path 3091"
                                                d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                                transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                        </svg>
                                    </div>
                                </div>
                            </form>
                            <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                                style="min-height: 200px">
                                <div class="search-preloader absolute-top-center">
                                    <div class="dot-loader">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="search-nothing d-none p-3 text-center fs-16">

                                </div>
                                <div id="search-content" class="text-left">

                                </div>
                            </div>
                            <ul class="list-inline mb-0 pl-0 hor-swipe c-scrollbar-light" style="overflow: visible;">
                                @if (get_setting('header_menu_labels') != null)
                                    @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                                        <li
                                            class="list-inline-item mr-0 animate-underline-white {{ $key == 1 ? 'category__mega_menu' : '' }}">
                                            <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                                                class="fs-13 px-3 py-3 d-inline-block fw-500 {{ $nav_txt_color }} header_menu_links
                                                @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif">
                                                {{ translate($value) }}
                                            </a>
                                            @if ($key == 1)
                                                <div class="drop_down_cat_menu">
                                                    <div class="bg-white px-3">
                                                        <div class="row ">
                                                            @foreach (featured_categories() as $key => $category)
                                                                @php
                                                                    $category_name = $category->getTranslation('name');
                                                                @endphp
                                                                <div
                                                                    class="col-xl-4 col-md-6  border-bottom py-3 py-md-2rem">
                                                                    <div class="d-sm-flex">
                                                                        <div class="mb-3">
                                                                            <img src="{{ isset($category->bannerImage->file_name) ? my_asset($category->bannerImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                                                class="lazyload w-150px h-auto mx-auto has-transition"
                                                                                alt="{{ $category->getTranslation('name') }}"
                                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                                        </div>
                                                                        <div class="px-2 px-lg-4">
                                                                            <h6 class="text-dark mb-0 text-truncate-2">
                                                                                <a class="text-reset fw-700 fs-14 hov-text-primary"
                                                                                    href="{{ route('products.category', $category->slug) }}"
                                                                                    title="{{ $category_name }}">
                                                                                    {{ $category_name }}
                                                                                </a>
                                                                            </h6>
                                                                            @foreach ($category->childrenCategories->take(5) as $key => $child_category)
                                                                                <p class="mb-0 mt-3">
                                                                                    <a href="{{ route('products.category', $child_category->slug) }}"
                                                                                        class="fs-13 fw-300 text-reset hov-text-primary animate-underline-primary">
                                                                                        {{ $child_category->getTranslation('name') }}
                                                                                    </a>
                                                                                </p>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="footer-top d-flex justify-content-end lg:mr-5">
                    <div class="footer-flag-wrapper">

                        <!-- USA flag button (default) -->
                        <button class="flag-btn" onclick="toggleFlags(event)">
                            <img src="{{ asset('public/assets/img/uk.jpg') }}" class="flag-icon" alt="USA">
                        </button>

                        <!-- Dropdown -->
                        <div class="flag-dropdown" id="flagDropdown">

                            <div class="flag-item d-flex align-items-center px-3 py-2 gap-2">
                                <img src="{{ asset('public/assets/img/usa.jpg') }}" width="20" height="14">
                                <span class="ms-1">On The Way</span>
                            </div>



                            <div class="flag-item d-flex align-items-center px-3 py-2 gap-2">
                                <img src="{{ asset('public/assets/img/flag.jpeg') }}" width="20" height="14">
                                <span class="ms-1">On The Way</span>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Search box -->
                {{-- <div class="ml-3 mr-0">
                        <div class="nav-search-box">
                            <a href="#" class="nav-box-link text-dark">
                            <svg class="svg-search icon icon--header-search" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.00002 1.6C4.4654 1.6 1.60002 4.46538 1.60002 8C1.60002 11.5346 4.4654 14.4 8.00002 14.4C11.5346 14.4 14.4 11.5346 14.4 8C14.4 4.46538 11.5346 1.6 8.00002 1.6ZM0.400024 8C0.400024 3.80264 3.80266 0.400002 8.00002 0.400002C12.1974 0.400002 15.6 3.80264 15.6 8C15.6 9.88268 14.9155 11.6055 13.7817 12.933L19.4243 18.5757C19.6586 18.8101 19.6586 19.19 19.4243 19.4243C19.19 19.6586 18.8101 19.6586 18.5758 19.4243L12.9332 13.7816C11.6056 14.9154 9.88275 15.6 8.00002 15.6C3.80266 15.6 0.400024 12.1974 0.400024 8Z" fill="currentColor"></path>
                            </svg>
                            </a>
                        </div>
                    </div> --}}
                {{-- @if (!isAdmin())
                        <!-- Notifications -->
                        <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
                            <li class="list-inline-item ml-3 mr-3 pr-3 pl-0 dropdown">
                                <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                    href="javascript:void(0);" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <span class="">
                                        <span class="position-relative d-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14.668" height="16"
                                                viewBox="0 0 14.668 16">
                                                <path id="_26._Notification" data-name="26. Notification"
                                                    d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                                                    transform="translate(-0.999)" fill="#91919b" />
                                            </svg>
                                            @if (Auth::check() && count($user->unreadNotifications) > 0)
                                                <span
                                                    class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count($user->unreadNotifications) }}</span>
                                            @endif
                                        </span>
                                </a>

                                @auth
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                        <div class="p-3 bg-light border-bottom">
                                            <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                        </div>
                                        <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                            <ul class="list-group list-group-flush">
                                                @forelse($user->unreadNotifications as $notification)
                                                    <li class="list-group-item">
                                                        @if ($notification->type == 'App\Notifications\OrderNotification')
                                                            @if ($user->user_type == 'customer')
                                                                <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                                                    class="text-secondary fs-12">
                                                                    <span class="ml-2">
                                                                        {{ translate('Order code: ') }}
                                                                        {{ $notification->data['order_code'] }}
                                                                        {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                    </span>
                                                                </a>
                                                            @elseif ($user->user_type == 'seller')
                                                                <a href="{{ route('seller.orders.show', encrypt($notification->data['order_id'])) }}"
                                                                    class="text-secondary fs-12">
                                                                    <span class="ml-2">
                                                                        {{ translate('Order code: ') }}
                                                                        {{ $notification->data['order_code'] }}
                                                                        {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </li>
                                                @empty
                                                    <li class="list-group-item">
                                                        <div class="py-4 text-center fs-16">
                                                            {{ translate('No notification found') }}
                                                        </div>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <div class="text-center border-top">
                                            <a href="{{ route('all-notifications') }}"
                                                class="text-secondary fs-12 d-block py-2">
                                                {{ translate('View All Notifications') }}
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </li>
                        </ul>
                    @endif --}}

                <div class="d-none d-xl-block mr-0">
                    @auth
                        <span
                            class="d-flex align-items-center nav-user-info user py-20px @if (isAdmin())  @endif"
                            id="nav-user-info">
                            <!-- Image -->
                            <span class="rounded-circle overflow-hidden border border-transparent"
                                style="width: 28px; height: 28px;">
                                @if ($user->avatar_original != null)
                                    <img src="{{ $user_avatar }}" class="img-fit h-100"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                            </span>
                            <!-- Name -->
                            <!-- <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4> -->
                            <h4 class="h5 fs-14 fw-500 text-dark ml-2 mb-0">Hi {{ $user->name }}!</h4>

                        </span>
                    @else
                        <!--Login & Registration -->
                        <span class="d-flex align-items-center nav-user-info ml-3">
                            <a href="{{ route('user.login') }}">
                                <!-- Image -->
                                <span
                                    class="size-40px rounded-circle overflow-hidden d-flex align-items-center justify-content-center ">
                                    <svg class="icon-user " aria-hidden="true" focusable="false" role="presentation"
                                        xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                        viewBox="0 0 26 26" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M13 24.5C19.3513 24.5 24.5 19.3513 24.5 13C24.5 6.64873 19.3513 1.5 13 1.5C6.64873 1.5 1.5 6.64873 1.5 13C1.5 19.3513 6.64873 24.5 13 24.5Z"
                                            stroke="#000" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path
                                            d="M4.95898 21.221C6.66657 20.2309 8.48298 19.4416 10.372 18.869C11.209 18.56 11.3 16.64 10.7 15.98C9.83398 15.027 9.09998 13.91 9.09998 11.214C8.99795 10.1275 9.36642 9.04944 10.1121 8.25272C10.8578 7.45599 11.9092 7.01703 13 7.047C14.0908 7.01703 15.1422 7.45599 15.8879 8.25272C16.6335 9.04944 17.002 10.1275 16.9 11.214C16.9 13.914 16.166 15.027 15.3 15.98C14.7 16.64 14.791 18.56 15.628 18.869C17.517 19.4416 19.3334 20.2309 21.041 21.221"
                                            stroke="#000" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </span>
                    @endauth
                </div>
                <!-- Wishlist -->
                <div class="d-none d-lg-block ml-3">
                    <div class="" id="wishlist">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.wishlist')
                    </div>
                </div>
                <div class="d-none d-xl-block align-self-stretch ml-2  mr-0 has-transition " data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items" style="width: max-content;">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.cart')
                    </div>
                </div>
            </div>
        </div>

        <!-- Loged in user Menus top-100  removed -->
        <div class="hover-user-top-menu position-absolute left-0 right-0 z-3">
            <div class="container">
                <div class="position-static float-right">
                    <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                        <ul class="list-unstyled no-scrollbar mb-0 text-left">
                            @if (isAdmin())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (isCustomer())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf" />
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf" />
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf" />
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf" />
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf" />
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                    </a>
                                </li>
                                {{-- <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('digital_purchase_history.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                                viewBox="0 0 16.001 16">
                                                <g id="Group_25262" data-name="Group 25262"
                                                    transform="translate(-1388.154 -562.604)">
                                                    <path id="Path_2963" data-name="Path 2963"
                                                        d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                        transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                                    <path id="Path_2964" data-name="Path 2964"
                                                        d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                        transform="translate(1324 486)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                        </a>
                                    </li> --}}
                                @if (get_setting('conversation_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('conversations.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <g id="Group_25263" data-name="Group 25263"
                                                    transform="translate(1053.151 256.688)">
                                                    <path id="Path_3012" data-name="Path 3012"
                                                        d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                        transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                    <path id="Path_3013" data-name="Path 3013"
                                                        d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                        transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                    <path id="Path_3014" data-name="Path 3014"
                                                        d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                    <path id="Path_3015" data-name="Path 3015"
                                                        d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                        transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (get_setting('wallet_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('wallet.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                                                height="16" viewBox="0 0 16 16">
                                                <defs>
                                                    <clipPath id="clip-path1">
                                                        <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                            width="16" height="16" fill="#b5b5bf" />
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_8102" data-name="Group 8102"
                                                    clip-path="url(#clip-path1)">
                                                    <path id="Path_2936" data-name="Path 2936"
                                                        d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                        fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                        </a>
                                    </li>
                                @endif
                                {{-- <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('support_ticket.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                                viewBox="0 0 16 16.001">
                                                <g id="Group_25259" data-name="Group 25259"
                                                    transform="translate(-316 -1066)">
                                                    <path id="Subtraction_184" data-name="Subtraction 184"
                                                        d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                        transform="translate(-16096 180)" fill="#b5b5bf" />
                                                    <path id="Union_12" data-name="Union 12"
                                                        d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                        transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                        </a>
                                    </li> --}}
                            @endif
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('logout') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503"
                                            transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533" />
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                height="8" rx="0.5" transform="translate(31.5 377)"
                                                fill="#d43533" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name text-primary has-transition ml-3">{{ translate('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $featured_categories = \Cache::rememberForever('featured_categories', function () {
            return \App\Models\Category::with('bannerImage')->where('featured', 1)->get();
        });
    @endphp

    <!-- Sliders -->
    <section class="my-2 mt-2">
        <div class="container">
            <!-- Categories -->
            <div class="row">
                <div class="col-md-12">
                    @if (count($featured_categories) > 0)
                        <div class="banner-category custom-banner-category"
                            style="overflow: hidden !important; position: relative !important;">
                            <ul class="aiz-carousel sm-gutters-16 arrow" data-items="9" data-xl-items="7"
                                data-lg-items="6" data-md-items="5" data-sm-items="5" data-xs-items="4"
                                data-arrows='true' data-infinite='false'
                                style="display: flex !important; flex-wrap: nowrap !important; list-style: none !important; padding: 0 !important; margin: 0 !important;">
                                @foreach ($featured_categories as $key => $category)
                                    @if ($key < 10)
                                        @php
                                            $category_name = $category->getTranslation('name');
                                        @endphp
                                        <li
                                            style="position: relative !important; flex: 0 0 auto !important; padding: 0 8px !important; text-align: center !important;">
                                            <a href="{{ route('products.category', $category->slug) }}"
                                                style="display: block !important;">
                                                <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                    alt="{{ $category_name }}" class="img-fluid"
                                                    style="max-width: 60px !important; max-height: 60px !important; width: auto !important; height: auto !important; margin: 0 auto !important; display: block !important;">
                                            </a>
                                            <a href="{{ route('products.category', $category->slug) }}"
                                                class="category_a">
                                                <span
                                                    style="custom-banner-description-text">{{ $category_name }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</header>

<!-- Top Menu Sidebar -->
<div class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar"
        data-same=".hide-top-menu-bar"></div>
    <div class="collapse-sidebar c-scrollbar-light text-left">
        <button type="button" class="btn btn-sm p-4 hide-top-menu-bar" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar">
            <i class="las la-times la-2x text-primary"></i>
        </button>
        @auth
            <span class="d-flex align-items-center nav-user-info pl-4">
                <!-- Image -->
                <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                    @if ($user->avatar_original != null)
                        <img src="{{ $user_avatar }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                            alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @endif
                </span>
                <!-- Name -->
                <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4>
            </span>
        @else
            <!--Login & Registration -->
            <span class="d-flex align-items-center nav-user-info pl-4">
                <!-- Image -->
                <span
                    class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012" viewBox="0 0 19.902 20.012">
                        <path id="fe2df171891038b33e9624c27e96e367"
                            d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                            transform="translate(-2.064 -1.995)" fill="#91919b" />
                    </svg>
                </span>
                <a href="{{ route('user.login') }}"
                    class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                <!-- <a href="{{ route('user.registration') }}"
                                class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>-->
            </span>
        @endauth
        <hr>
        <ul class="mb-0 pl-3 pb-3  ethe">
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    <li class="mr-0">
                        <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                            @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif">
                            {{ translate($value) }}
                        </a>
                    </li>
                @endforeach
            @endif
            @auth
                @if (isAdmin())
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('admin.dashboard') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @else
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('dashboard') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['dashboard'], ' active') }}">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @endif
                @if (isCustomer())
                    <li class="mr-0">
                        <a href="{{ route('all-notifications') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['all-notifications'], ' active') }}">
                            {{ translate('Notifications') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('wishlists.index') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            {{ translate('Wishlist') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('compare') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['compare'], ' active') }}">
                            {{ translate('Compare') }}
                        </a>
                    </li>
                @endif
                <hr>
                <li class="mr-0">
                    <a href="{{ route('logout') }}"
                        class="fs-13 px-3 ethe py-3 w-100 d-inline-block fw-700 text-primary header_menu_links">
                        {{ translate('Logout') }}
                    </a>
                </li>
            @endauth
            <li class="mb-2 pb-2  active">
                <a href="{{ url('') }}" class="fs-13 text-dark text-sm-secondary animate-underline-white">
                    Home
                </a>
            </li>
            <li class="mb-2 pb-2  active">
                <a href="{{ url('about-us') }}" class="fs-13 text-dark text-sm-secondary animate-underline-white">
                    About Us
                </a>
            </li>
            <li class="mb-2 pb-2  active">
                <a href="{{ url('categories') }}" class="fs-13 text-dark text-sm-secondary animate-underline-white">
                    Categories
                </a>
            </li>
            {{--  <li class="mb-2 pb-2  active">
                            <a href="{{url('brands')}}" class="fs-13 text-dark text-sm-secondary animate-underline-white">
                                Brands
                            </a>
                        </li> --}}
            <li class="mb-2 pb-2  active">
                <a href="{{ url('blog') }}" class="fs-13 text-dark text-sm-secondary animate-underline-white">
                    Blogs
                </a>
            </li>
            <li class="mb-2 pb-2  active">
                <a href="{{ url('contact-us') }}" class="fs-13 text-dark text-sm-secondary animate-underline-white">
                    Contact Us
                </a>
            </li>
            <li class="mb-2 pb-2  active">
                <a href="{{ url('career') }}" class="fs-13 text-dark text-sm-secondary animate-underline-white">
                    Carrer
                </a>
            </li>
        </ul>
        <div class="ml-2 footer-social">
            <!-- Social -->
            @if (get_setting('show_social_links'))
                <h5 class="fs-14 fw-700 text-secondary text-uppercase mt-3 mt-lg-0">{{ translate('Follow Us') }}</h5>
                <ul class="list-inline social colored mb-4">
                    @if (!empty(get_setting('facebook_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook"><i
                                    class="lab la-facebook-f"></i></a>
                        </li>
                    @endif
                    @if (!empty(get_setting('twitter_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('twitter_link') }}" target="_blank" class="twitter"><img
                                    src="{{ static_asset('assets/img/x-logo.png') }}"
                                    alt="{{ translate('x') }}"></a>
                        </li>
                    @endif
                    @if (!empty(get_setting('instagram_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram"><i
                                    class="lab la-instagram"></i></a>
                        </li>
                    @endif
                    @if (!empty(get_setting('youtube_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i
                                    class="lab la-youtube"></i></a>
                        </li>
                    @endif
                    @if (!empty(get_setting('linkedin_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('linkedin_link') }}" target="_blank" class="linkedin"><i
                                    class="lab la-linkedin-in"></i></a>
                        </li>
                    @endif
                    @if (!empty(get_setting('pinterest_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('pinterest_link') }}" target="_blank" class="linkedin"><i
                                    class="lab la-pinterest"></i></a>
                        </li>
                    @endif
                    <li class="list-inline-item ml-2 mr-2">
                        <a href="{{ get_setting('pinterest_link') }}" target="_blank" class="linkedin"><i
                                class="lab la-pinterest"></i></a>
                    </li>

                </ul>
            @endif
        </div>
        <br>
        <br>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="order-details-modal-body">

            </div>
        </div>
    </div>
</div>
<style>
    .user {
        margin-left: 10px;
    }

    .flag-dropdown div {
        white-space: nowrap;
        /* text wrap se bachata hai */
    }

    .flag-dropdown {
        position: absolute;
        top: 100%;
        margin-top: 4px;

        right: 50%;
        transform: translateX(50%);

        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);

        overflow: hidden;
        padding: 4px 0;

        width: max-content;
        /* 🔥 FIX */
        min-width: unset;
        /* 🔥 REMOVE */

        display: none;
        flex-direction: column;
        z-index: 50;
    }

    .flag-item {
        padding: 8px 12px;
        /* 🔧 balanced padding */
        white-space: nowrap;
        gap: 10px;
    }


    @media (max-width: 768px) {
        .flag-dropdown {
            right: 8px;
            transform: none;
        }

        .flag-item {
            padding-left: 12px;
            padding-right: 12px;
        }
    }

    .btnNav {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .btnNav:hover,
    .btnNav:focus,
    .btnNav:active {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
</style>

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function(data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
@endsection
