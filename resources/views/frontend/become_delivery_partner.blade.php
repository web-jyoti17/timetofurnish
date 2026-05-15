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
                <h1 class="fw-600 h4">Become Our Delivery Partners</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                        <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        "Become Our Delivery Partners"
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="mb-4">
	<div class="container">
        <div class="bg-theme jumbotron row">
              @if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
            
            <div class="contact-form col-md-8 m-auto">
                <form action="{{ route('delivery.partner.submit') }}" method="POST" id="contact-form">
                    @csrf
                      
                      
                    <h2>Join Our Delivery Team</h2>
                    <div class="input-box">
                    <input type="text" required="true" name="company_name" placeholder="Company Name">
                    <!--<span>Company Name</span>-->
                    </div>
                    
                    <div class="input-box">
                    <input type="email" required="true" name="email" placeholder="Email">
                    <!--<span>Email</span>-->
                    </div>
                    <div class="input-box">
                        <input type="tel" required="true" name="contact_number" placeholder="Contact Number">
                        <!--<span>Contact Number</span>-->
                    </div>
                    <div class="input-box">
                        <textarea required="true" name="area_coverage" placeholder="Area of Coverage"></textarea>
                        <!--<span>Area of Coverage</span>-->
                    </div>
                    <div class="input-box">
                        <textarea required="true" name="services_provided" placeholder="Services Provided"></textarea>
                        <!--<span>Services Provided</span>-->
                    </div>
                    <div class="input-box">
                    <input type="submit" value="Submit Request" name="">
                    </div>
                </form>
            
            </div>
            
        </div>
	</div>
</section>
@endsection
