@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 fw-700 text-primary">{{ translate('Create New Offer') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('seller.offers.index') }}" class="btn btn-link text-secondary">
                <i class="las la-arrow-left mr-2"></i>{{ translate('Back to Offers List') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <form action="{{ route('seller.offers.store') }}" method="POST">
            @csrf
            @include('backend.marketing.offers.form')
        </form>
    </div>
</div>

@endsection
