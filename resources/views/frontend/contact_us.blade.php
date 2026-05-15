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
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4">Contact Us</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
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
<section class="mb-4">
	<div class="container">
        <div class="bg-theme jumbotron row">
            
            <div class="contact-info col-md-6">
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                    <i class="las la-map-marker"></i>
                    </div>
                    
                    <div class="contact-info-content">
                        <h4>Address</h4>
                        <p>20 Wenlock Road<br> London,  England <br> N1 7GU</p>
                    </div>
                 </div>
                 
                <div class="contact-info-item ">
                    <div class="contact-info-icon">
                        <i class="lab la-whatsapp"></i>
                    </div>
                    
                    <div class="contact-info-content">
                    <h4>WhatsApp
</h4>
                    <p><a href="https://wa.me/447751510365" target="_blank">+44 7751 510365
</a></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                    <i class="las la-envelope"></i>
                    </div>
                    
                    <div class="contact-info-content">
                    <h4>Email</h4>
                    <p><a href="mailto:askus@timetofurnish.com" class="text-dark">askus@timetofurnish.com</a></p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form col-md-6">
                  @if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
            <form action="{{route('contact_us.submit')}}" method="POST">
    @csrf

                    <h2>Send Message</h2>
                    <div class="input-box">
                    <input type="text" required="true" name="name" placeholder="Full Name">
                    <!--<span>Full Name</span>-->
                    </div>
                    
                    <div class="input-box">
                    <input type="email" required="true" name="email" placeholder="Email">
                    <!--<span>Email</span>-->
                    </div>
                    <div class="input-box">
                    <input type="tel" required="true" name="phone" placeholder="Phone">
                    <!--<span>Phone</span>-->
                    </div>
                    <div class="input-box">
                    <textarea required="true" name="message" placeholder="Type your Message..."></textarea>
                    <!--<span>Type your Message...</span>-->
                    </div>
                    
                    <div class="input-box">
                    <input type="submit" value="Send" name="">
                    </div>
                </form>
            </div>
            
        </div>
	</div>
</section>
@endsection
