@extends('frontend.layouts.app')
{{--
@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection--}}

@section('content')
<section class="pt-4 mb-4  breadcrumb-banner">
    <div class="container text-center">
        <div class="row">
          {{--  <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4">Contact Us</h1>
            </div>--}}
            <div class="col-lg-12">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-center  breadcrumbfont">
                    <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                        <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        "Contact Us"
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
	<div class="container">
        <div class="modern-contact-card shadow-sm overflow-hidden mx-auto">
            <div class="row no-gutters">

                <!-- Left Column: Contact Info -->
                <div class="col-md-5 modern-contact-info d-flex flex-column justify-content-between p-4 p-md-5">
                    <div>
                        <span class="contact-eyebrow text-uppercase fw-700 fs-12">Get in Touch</span>
                        <h2 class="mt-2 mb-4 fw-800">We'd Love to Hear From You</h2>
                        <p class="mb-5 fs-14">Have any questions about our furniture, custom orders, or shipping? Reach out to us and we'll get back to you as soon as possible.</p>

                        <div class="modern-info-items">
                            <div class="d-flex align-items-start mb-4">
                                <div class="modern-info-icon mr-3 d-flex align-items-center justify-content-center">
                                    <i class="las la-map-marker" style="font-size: 22px;"></i>
                                </div>
                                <div>
                                    <h5 class="fs-14 fw-700 mb-1">Our Office</h5>
                                    <p class="mb-0 fs-13">20 Wenlock Road<br>London, England, N1 7GU</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="modern-info-icon mr-3 d-flex align-items-center justify-content-center">
                                    <i class="lab la-whatsapp" style="font-size: 22px;"></i>
                                </div>
                                <div>
                                    <h5 class="fs-14 fw-700 mb-1">WhatsApp Chat</h5>
                                    <p class="mb-0 fs-13">
                                        <a href="https://wa.me/447751510365" target="_blank" rel="noopener">+44 7751 510365</a>
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="modern-info-icon mr-3 d-flex align-items-center justify-content-center">
                                    <i class="las la-envelope" style="font-size: 22px;"></i>
                                </div>
                                <div>
                                    <h5 class="fs-14 fw-700 mb-1">Email Support</h5>
                                    <p class="mb-0 fs-13">
                                        <a href="mailto:askus@timetofurnish.com">askus@timetofurnish.com</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Contact Form -->
                <div class="col-md-7 modern-contact-form p-4 p-md-5 bg-white">
                    @if(session()->has('success'))
                        <div class="alert alert-success border-0 rounded-3 px-4 py-3 mb-4 fs-14" style="background-color: #f0fdf4; color: #166534;">
                            <i class="las la-check-circle mr-2" style="font-size: 16px;"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 px-4 py-3 mb-4 fs-14" style="background-color: #fef2f2; color: #991b1b;">
                            <i class="las la-exclamation-circle mr-2" style="font-size: 16px;"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form action="{{route('contact_us.submit')}}" method="POST">
                        @csrf
                        <h3 class="fw-800 mb-4" style="color: #39322a; font-size: 24px;">Send us a Message</h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="fs-12 fw-700 mb-2 text-uppercase tracking-wider" style="color: #4f4238; font-size: 11px;">Full Name</label>
                                    <input type="text" required class="form-control" name="name" placeholder="Full Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="fs-12 fw-700 mb-2 text-uppercase tracking-wider" style="color: #4f4238; font-size: 11px;">Email Address</label>
                                    <input type="email" required class="form-control" name="email" placeholder="Email Address">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="fs-12 fw-700 mb-2 text-uppercase tracking-wider" style="color: #4f4238; font-size: 11px;">Phone Number</label>
                            <input type="tel" required class="form-control" name="phone" placeholder="Phone Number">
                        </div>

                        <div class="form-group mb-4">
                            <label class="fs-12 fw-700 mb-2 text-uppercase tracking-wider" style="color: #4f4238; font-size: 11px;">Your Message</label>
                            <textarea required class="form-control" name="message" rows="5" placeholder="Type your Message..."></textarea>
                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-modern-submit w-100 py-3 fw-700 text-uppercase tracking-wider fs-13">
                                Send Message <i class="las la-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
	</div>
</section>

<style>
.modern-contact-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #eadfd3;
    overflow: hidden;
    box-shadow: 0 18px 45px rgba(104, 91, 78, 0.08) !important;
    max-width: 1000px;
}

.modern-contact-info {
    background: linear-gradient(145deg, #fff9f2 0%, #f3e8da 100%) !important;
    color: #39322a;
    position: relative;
    overflow: hidden;
}

.modern-contact-info::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(104, 91, 78, 0.08) 0%, rgba(104, 91, 78, 0) 68%);
    pointer-events: none;
}

.contact-eyebrow {
    color: #8a6f4d;
    letter-spacing: 1.5px;
}

.modern-contact-info h2 {
    color: #2f2924;
    font-size: 28px;
    line-height: 1.2;
}

.modern-contact-info p {
    color: #6f6257;
    line-height: 1.6;
}

.modern-info-items h5 {
    color: #3d342d;
}

.modern-info-icon {
    width: 44px;
    height: 44px;
    background: #ffffff;
    border: 1px solid #eadfd3;
    border-radius: 50%;
    color: #685b4e;
    flex-shrink: 0;
    box-shadow: 0 8px 18px rgba(104, 91, 78, 0.08);
}

.modern-info-items a {
    color: #685b4e;
    text-decoration: underline;
    text-underline-offset: 3px;
    transition: color 0.2s ease;
}

.modern-info-items a:hover {
    color: #3d342d;
}

.modern-contact-form {
    background-color: #ffffff;
}

.modern-contact-form .form-group label {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.modern-contact-form .form-control {
    background: #fdfcfa;
    border: 1.5px solid #eadfd3;
    border-radius: 8px;
    padding: 12px 16px;
    height: auto;
    font-size: 14px;
    color: #39322a;
    transition: all 0.3s ease;
}

.modern-contact-form .form-control::placeholder {
    color: #a3968a;
}

.modern-contact-form .form-control:focus {
    background: #ffffff;
    border-color: #685b4e;
    box-shadow: 0 0 0 4px rgba(104, 91, 78, 0.12);
    outline: none;
}

.modern-contact-form textarea.form-control {
    resize: none;
}

.btn-modern-submit {
    background-color: #685b4e !important;
    color: #ffffff !important;
    border: none;
    border-radius: 30px !important;
    box-shadow: 0 8px 20px rgba(104, 91, 78, 0.15);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.btn-modern-submit:hover {
    background-color: #53483e !important;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(104, 91, 78, 0.25);
    text-decoration: none !important;
}

.btn-modern-submit:active {
    transform: translateY(0);
}

@media (max-width: 767.98px) {
    .modern-contact-card {
        border-radius: 14px;
    }

    .modern-contact-info,
    .modern-contact-form {
        padding: 24px !important;
    }

    .modern-contact-info h2 {
        font-size: 24px;
    }

    .modern-contact-info p {
        margin-bottom: 28px !important;
    }

    .modern-contact-form h3 {
        font-size: 21px !important;
    }

    .btn-modern-submit {
        padding-top: 13px !important;
        padding-bottom: 13px !important;
    }
}
</style>
@endsection
