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
                <button type="button" class="btnNav d-lg-none mr-3 mr-sm-4 p-0 active d-flex align-items-center"
                    data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                        fill="none" stroke="#685b4e" stroke-width="1.75" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
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
                <div class="d-lg-none small ml-auto mr-0 d-flex align-items-center">
                    <a class="p-2 d-block text-reset d-flex align-items-center" href="javascript:void(0);"
                        data-toggle="class-toggle" data-target=".front-header-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="#685b4e" stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </a>
                </div>
                <div class="mobile_search front-header-search flex-grow-1 px-3 px-lg-0">
                    <form action="{{ route('search') }}" method="GET" class="stop-propagation ">
                        <div class="d-flex position-relative align-items-center">
                            <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                <button class="btn px-2 d-flex align-items-center" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="#685b4e" stroke-width="1.75"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                </button>
                            </div>
                            <div class="search-input-box">
                                <input type="text"
                                    class="border border-soft-light form-control fs-14 hov-animate-outline"
                                    id="search_mobile" name="keyword"
                                    @isset($query)
                                    value="{{ $query }}"
                                    @endisset
                                    placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="#8c8276" stroke-width="1.75"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
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
                                        <button class="btn px-2 d-flex align-items-center" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                viewBox="0 0 24 24" fill="none" stroke="#685b4e"
                                                stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="19" y1="12" x2="5" y2="12">
                                                </line>
                                                <polyline points="12 19 5 12 12 5"></polyline>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="search-input-box">
                                        <input type="text"
                                            class="border border-soft-light form-control fs-14 hov-animate-outline"
                                            id="search" name="keyword"
                                            @isset($query)
                                            value="{{ $query }}"
                                            @endisset
                                            placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                        <svg class="clear-search-icon" xmlns="http://www.w3.org/2000/svg"
                                            width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="#8c8276" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="#8c8276" stroke-width="1.75"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65">
                                            </line>
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
                            <img src="{{ static_asset('assets/img/uk.jpg') }}" class="flag-icon" alt="USA">
                        </button>

                        <!-- Dropdown -->
                        <div class="flag-dropdown" id="flagDropdown">

                            <div class="flag-item d-flex align-items-center px-3 py-2 gap-2">
                                <img src="{{ static_asset('assets/img/usa.jpg') }}" width="20" height="14">
                                <span class="ms-1">On The Way</span>
                            </div>



                            <div class="flag-item d-flex align-items-center px-3 py-2 gap-2">
                                <img src="{{ static_asset('assets/img/flag.jpeg') }}" width="20" height="14">
                                <span class="ms-1">On The Way</span>
                            </div>
                        </div>


                    </div>
                </div>


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
                                    <svg class="icon-user" xmlns="http://www.w3.org/2000/svg" width="22"
                                        height="22" viewBox="0 0 24 24" fill="none" stroke="#685b4e"
                                        stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
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
                <div class="d-none d-xl-block  ml-2  mr-0 has-transition " data-hover="dropdown">
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
                                style="display: flex !important; flex-wrap: wrap !important; list-style: none !important; padding: 0 !important; margin: 0 !important;">
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
    <div class="collapse-sidebar c-scrollbar-light text-left mobile-menu-drawer">
        <div class="mobile-menu-head">
            <a href="{{ route('home') }}" class="mobile-menu-brand">
                @if ($header_logo != null)
                    <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}">
                @endif
            </a>
            <button type="button"
                class="btn btn-sm hide-top-menu-bar mobile-menu-close d-flex align-items-center justify-content-center"
                data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar"
                aria-label="{{ translate('Close') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                    fill="none" stroke="#685b4e" stroke-width="1.75" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        @auth
            <div class="mobile-menu-user">
                <span class="nav-user-img">
                    @if ($user->avatar_original != null)
                        <img src="{{ $user_avatar }}" class="img-fit h-100 sajdhgfjakhdgfjs"
                            alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                            alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @endif
                </span>
                <div class="mobile-menu-user-copy">
                    <span>{{ translate('Welcome back') }}</span>
                    <h4>{{ $user->name }}</h4>
                </div>
            </div>
        @else
            <div class="mobile-menu-user">
                <span class="nav-user-img">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="#685b4e" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </span>
                <div class="mobile-menu-user-copy">
                    <span>{{ translate('Account') }}</span>
                    <a href="{{ route('user.login') }}">{{ translate('Login') }}</a>
                </div>
            </div>
        @endauth

        <div class="mobile-menu-section-title">{{ translate('Menu') }}</div>
        <ul class="mb-0 pl-0 pb-3 ethe mobile-menu-list">
            <li class="mr-0">
                <a href="{{ url('') }}" class="@if (request()->is('/')) active @endif">
                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <span>Home</span>
                </a>
            </li>
            <li class="mr-0">
                <a href="{{ url('about-us') }}" class="@if (request()->is('about-us')) active @endif">
                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>About Us</span>
                </a>
            </li>
            <li class="mr-0">
                <a href="{{ url('categories') }}" class="@if (request()->is('categories')) active @endif">
                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Categories</span>
                </a>
            </li>
            <li class="mr-0">
                <a href="{{ url('blog') }}" class="@if (request()->is('blog') || request()->is('blog/*')) active @endif">
                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    <span>Blogs</span>
                </a>
            </li>
            <li class="mr-0">
                <a href="{{ url('contact-us') }}" class="@if (request()->is('contact-us')) active @endif">
                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Contact Us</span>
                </a>
            </li>
            <li class="mr-0">
                <a href="{{ url('career') }}" class="@if (request()->is('career')) active @endif">
                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    <span>Career</span>
                </a>
            </li>
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    <li class="mr-0">
                        <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                            class="@if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif">
                            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                            <span>{{ translate($value) }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
            @auth
                @if (isAdmin())
                    <li class="mr-0 mobile-menu-account-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>{{ translate('My Account') }}</span>
                        </a>
                    </li>
                @else
                    <li class="mr-0 mobile-menu-account-item">
                        <a href="{{ route('dashboard') }}"
                            class="{{ areActiveRoutes(['dashboard'], ' active') }}">
                            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>{{ translate('My Account') }}</span>
                        </a>
                    </li>
                @endif
                @if (isCustomer())
                    <li class="mr-0">
                        <a href="{{ route('all-notifications') }}"
                            class="{{ areActiveRoutes(['all-notifications'], ' active') }}">
                            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                            <span>{{ translate('Notifications') }}</span>
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('wishlists.index') }}"
                            class="{{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <span>{{ translate('Wishlist') }}</span>
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('compare') }}"
                            class="{{ areActiveRoutes(['compare'], ' active') }}">
                            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            <span>{{ translate('Compare') }}</span>
                        </a>
                    </li>
                @endif
                <li class="mr-0 mobile-menu-logout-item">
                    <a href="{{ route('logout') }}" class="logout-link">
                        <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        <span>{{ translate('Logout') }}</span>
                    </a>
                </li>
            @endauth
        </ul>
        <div class="ml-2 footer-social">
            <!-- Social -->
            @if (get_setting('show_social_links'))
                <h5 class="fs-14 fw-700 text-secondary text-uppercase mt-3 mt-lg-0">{{ translate('Follow Us') }}</h5>
                <ul class="list-inline social colored mb-4">
                    @if (!empty(get_setting('facebook_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('facebook_link') }}" target="_blank" rel="noopener"
                                class="facebook" aria-label="{{ translate('Facebook') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                </svg>
                            </a>
                        </li>
                    @endif
                    @if (!empty(get_setting('twitter_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('twitter_link') }}" target="_blank" rel="noopener"
                                class="twitter" aria-label="{{ translate('X') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                        </li>
                    @endif
                    @if (!empty(get_setting('instagram_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('instagram_link') }}" target="_blank" rel="noopener"
                                class="instagram" aria-label="{{ translate('Instagram') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                </svg>
                            </a>
                        </li>
                    @endif
                    @if (!empty(get_setting('youtube_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('youtube_link') }}" target="_blank" rel="noopener"
                                class="youtube" aria-label="{{ translate('YouTube') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/>
                                    <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="currentColor"/>
                                </svg>
                            </a>
                        </li>
                    @endif
                    @if (!empty(get_setting('linkedin_link')))
                        <li class="list-inline-item ml-2 mr-2">
                            <a href="{{ get_setting('linkedin_link') }}" target="_blank" rel="noopener"
                                class="linkedin" aria-label="{{ translate('LinkedIn') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                                    <rect x="2" y="9" width="4" height="12"/>
                                    <circle cx="4" cy="4" r="2" fill="currentColor"/>
                                </svg>
                            </a>
                        </li>
                    @endif
                    @if (!empty(get_setting('pinterest_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('pinterest_link') }}" target="_blank" class="pinterest" aria-label="Pinterest">
                                    <i class="lab la-pinterest-p"></i>
                                </a>
                            </li>
                        @endif

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

    .aiz-top-menu-sidebar .overlay.dark {
        background: rgba(79, 66, 56, 0.42) !important;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }

    .mobile-menu-drawer {
        width: min(88vw, 360px) !important;
        max-width: 360px !important;
        background: #fffdf9 !important;
        border-right: 1px solid #d8c8b7 !important;
        box-shadow: 18px 0 42px rgba(79, 66, 56, 0.18) !important;
        padding: 0 16px 22px !important;
    }

    .mobile-menu-head {
        position: sticky;
        top: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 0 -16px;
        padding: 18px 16px 14px;
        background: #fffdf9;
        border-bottom: 1px solid #eadfd3;
    }

    .mobile-menu-brand {
        display: inline-flex;
        align-items: center;
        min-width: 0;
    }

    .mobile-menu-brand img {
        display: block;
        max-width: 150px;
        max-height: 42px;
        object-fit: contain;
    }

    .mobile-menu-close {
        width: 38px;
        height: 38px;
        min-width: 38px;
        padding: 0 !important;
        border: 1px solid #d8c8b7 !important;
        border-radius: 50% !important;
        background: #ffffff !important;
        box-shadow: 0 8px 18px rgba(104, 91, 78, 0.08) !important;
    }

    .mobile-menu-user {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 16px 0 18px;
        padding: 14px;
        border: 0 !important;
        border-radius: 14px !important;
        background:linear-gradient(135deg, rgb(111 99 88 / 0%), rgba(246, 238, 228, 0.72)), linear-gradient(135deg, #f7efe6 0%, #fffdf9 100%);
        box-shadow: 0 14px 32px rgba(104, 91, 78, 0.1);
        position: relative;
        overflow: hidden;
    }

    .mobile-menu-user::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.42), rgba(255, 255, 255, 0));
        pointer-events: none;
    }

    .mobile-menu-user .nav-user-img {
        width: 48px !important;
        height: 48px !important;
        min-width: 48px;
        border: none !important;
        border-radius: 14px !important;
        background: #ffffff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 8px 18px rgba(104, 91, 78, 0.12);
        position: relative;
        z-index: 1;
    }

    .mobile-menu-user .nav-user-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mobile-menu-user-copy {
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .mobile-menu-user-copy span {
        display: block;
        color: #8c8177;
        font-size: 10px;
        font-weight: 600;
        line-height: 1;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .mobile-menu-user-copy h4,
    .mobile-menu-user-copy a {
        display: block;
        margin: 0;
        color: #3d342d !important;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.25;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }

    .mobile-menu-user-copy a:hover {
        color: #8a6f4d !important;
    }

    .mobile-menu-section-title {
        color: #8c8177;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.6px;
        margin: 6px 0 12px;
        text-transform: uppercase;
    }

    .mobile-menu-list {
        list-style: none !important;
        margin: 0 !important;
    }

    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

  

    .mobile-menu-list li {
        margin: 0 !important;
        padding: 0 !important;
    }

    .mobile-menu-list li::before,
    .mobile-menu-list li::after {
        content: none !important;
        display: none !important;
    }

    .mobile-menu-list li a {
        position: relative;
        display: flex !important;
        align-items: center;
        box-sizing: border-box;
        min-height: 48px;
        width: 100%;
        padding: 12px 34px 12px 8px !important;
        border: 0 !important;
        border-bottom: 1px solid #f2e9de !important;
        border-radius: 0;
        color: #4f4238 !important;
        background: transparent;
        font-size: 13.5px !important;
        font-weight: 550 !important;
        letter-spacing: 0.1px !important;
        line-height: 1.25;
        text-decoration: none !important;
        text-transform: none !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mobile-menu-list li a svg {
        stroke: currentColor;
        transition: stroke 0.25s ease, transform 0.25s ease;
        flex-shrink: 0;
    }

    .mobile-menu-list li a:hover svg,
    .mobile-menu-list li a.active svg {
        transform: scale(1.05);
    }

    .mobile-menu-list li a::before {
        content: none !important;
        display: none !important;
    }

    .mobile-menu-list li a::after {
        content: "›" !important;
        position: absolute;
        right: 14px;
        top: 50%;
        width: auto;
        height: auto;
        border: 0 !important;
        background: transparent !important;
        color: #9a8d80;
        font-size: 24px;
        font-weight: 300;
        line-height: 1;
        transform: translateY(-50%);
        transition: right 0.25s ease, color 0.25s ease;
    }

    .mobile-menu-list li>a>i,
    .mobile-menu-list li>a>.la,
    .mobile-menu-list li>a>.las {
        display: none !important;
    }

    .mobile-menu-list li:last-child a {
        border-bottom: 0 !important;
    }

    .mobile-menu-list li.mobile-menu-account-item a {
        margin-top: 12px;
        padding-top: 14px !important;
    }

    .mobile-menu-list li.mobile-menu-logout-item a {
        margin-top: 10px;
        border-bottom: 0 !important;
    }

    .mobile-menu-list li a.animate-underline-white::before,
    .mobile-menu-list li a.animate-underline-white::after,
    .mobile-menu-list li a.text-sm-secondary::before,
    .mobile-menu-list li a.text-sm-secondary::after {
        content: none !important;
        display: none !important;
    }

    .mobile-menu-list li a:hover,
    .mobile-menu-list li a.active {
        background: #fcf9f5 !important;
        color: #8a6f4d !important;
        padding-left: 14px !important;
        border-radius: 8px !important;
        border-bottom-color: transparent !important;
    }

    .mobile-menu-list li a:hover::after,
    .mobile-menu-list li a.active::after {
        right: 10px;
        color: #8a6f4d;
    }

    .mobile-menu-list li a.logout-link {
        color: #d43533 !important;
    }

    .mobile-menu-list li a.logout-link svg {
        stroke: #d43533 !important;
    }

    .mobile-menu-list li a.logout-link:hover {
        background: #fff5f5 !important;
        color: #b92c2a !important;
    }

    .mobile-menu-list li a.logout-link:hover svg {
        stroke: #b92c2a !important;
    }

    .mobile-menu-drawer .footer-social {
        margin: 16px 0 0 !important;
        padding: 16px 2px 0;
        border-top: 1px solid #eadfd3;
    }

    .mobile-menu-drawer .footer-social h5 {
        color: #6f6257 !important;
        font-size: 12px !important;
        font-weight: 750 !important;
        letter-spacing: 0 !important;
        line-height: 1.25;
        margin-bottom: 13px !important;
        text-transform: none !important;
    }

    .mobile-menu-drawer .footer-social .social {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        gap: 10px;
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: none;
    }

    .mobile-menu-drawer .footer-social .social::-webkit-scrollbar {
        display: none;
    }

    .mobile-menu-drawer .footer-social .social li {
        display: inline-flex !important;
        flex: 0 0 auto !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .mobile-menu-drawer .footer-social .social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border: 1px solid #eadfd3;
        border-radius: 999px;
        background: #ffffff;
        color: #685b4e !important;
        box-shadow: 0 10px 24px rgba(104, 91, 78, 0.08);
        text-decoration: none !important;
        transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .mobile-menu-drawer .footer-social .social a:hover,
    .mobile-menu-drawer .footer-social .social a:focus {
        background: #685b4e;
        border-color: #685b4e;
        color: #ffffff !important;
        box-shadow: 0 8px 20px rgba(104, 91, 78, 0.18);
        outline: none;
        transform: translateY(-1px);
    }

    .mobile-menu-drawer .footer-social .social a svg,
    .mobile-menu-drawer .footer-social .social a i {
        color: currentColor !important;
        transition: transform 0.2s ease;
        flex-shrink: 0;
    }

    .mobile-menu-drawer .footer-social .social a i {
        font-size: 20px;
        line-height: 1;
    }

    .mobile-menu-drawer .footer-social .social a:hover svg,
    .mobile-menu-drawer .footer-social .social a:focus svg,
    .mobile-menu-drawer .footer-social .social a:hover i,
    .mobile-menu-drawer .footer-social .social a:focus i {
        transform: scale(1.1);
    }

    @media (max-width: 380px) {
        .mobile-menu-drawer {
            width: 92vw !important;
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        .mobile-menu-head {
            margin-left: -14px;
            margin-right: -14px;
        }
    }
</style>

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            // Fixed the route name by removing the trailing space
            $.post('{{ route('orders.details') }}', {
                    _token: AIZ.data.csrf,
                    order_id: order_id
                },
                function(data) {
                    $('#order-details-modal-body').html(data);
                    $('#order_details').modal();
                    $('.c-preloader').hide();
                    AIZ.plugins.bootstrapSelect('refresh');
                });
        }
    </script>
@endsection
