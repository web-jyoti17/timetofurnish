@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Meet the Team') }} - {{ get_setting('website_name') }}@stop
@section('meta_description'){{ translate('Meet the team behind the brand and learn more about their experience and expertise.') }}@stop

@section('content')
<?php
    $bannerImage = get_setting('team_members_banner_image');
    $bannerBg = $bannerImage ? asset($bannerImage) : asset('assets/img/team/team-banner.png');
    $bannerTitle = get_setting('team_members_banner_title', translate('Meet Our Team'));
    $bannerSubtitle = get_setting('team_members_banner_subtitle', translate('Discover the team members who design, build, and support your products. Each profile includes a short introduction so your visitors can get to know the people behind the brand.'));
    $bannerDescription = get_setting('team_members_banner_description', '');
    $cardFallback = get_setting('team_members_card_image');
?>

<section class="py-6 position-relative overflow-hidden" style="background: linear-gradient(rgba(57,50,42,0.6), rgba(57,50,42,0.6)), center / cover no-repeat url('{{ $bannerBg }}');">
    <div class="shape rounded-circle bg-white opacity-10 position-absolute" style="width: 220px; height: 220px; top: -60px; right: -60px;"></div>
    <div class="shape rounded-circle bg-white opacity-10 position-absolute" style="width: 140px; height: 140px; bottom: -40px; left: 20px;"></div>
    <div class="container text-white position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-3" style="color: #dacbbc;">{{ $bannerTitle }}</h1>
                <p class="lead mb-4" style="color: rgba(218, 203, 188, 0.9);">{{ $bannerSubtitle }}</p>
                @if($bannerDescription)
                    <p style="color: rgba(218, 203, 188, 0.8);">{{ $bannerDescription }}</p>
                @endif
                <nav aria-label="breadcrumb" class="mt-4">
                    <ol class="breadcrumb bg-transparent px-0 mb-0" style="--bs-breadcrumb-divider: '>' ;">
                        <li class="breadcrumb-item"><a class="text-white-75" href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ translate('Meet the Team') }}</li>
                    </ol>
                </nav>
            </div>
            <!-- right preview removed to use full-width background with overlay -->
        </div>
    </div>
</section>

<section class="py-5" style="background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-xl-8 col-lg-9 text-center">
                <p class="text-uppercase mb-2" style="color: #685b4e;">{{ translate('Our professionals') }}</p>
                <h2 class="fw-bold" style="color: #39322a;">{{ translate('Empowered by experience, driven by results') }}</h2>
                <p class="text-muted mt-3">{{ translate('Every team member brings skills, passion, and deep product knowledge to help your business succeed.') }}</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($team_members as $member)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100" style="border-radius:16px; overflow:hidden; box-shadow:0 12px 30px rgba(57,50,42,0.06); border:1px solid rgba(57,50,42,0.04);">
                        <div style="background:#ffffff;">
                            @if($member->photo)
                                <div style="width:100%;height:220px;background:center/cover no-repeat url('{{ asset($member->photo) }}');"></div>
                            @elseif($cardFallback)
                                <div style="width:100%;height:220px;background:center/cover no-repeat url('{{ asset($cardFallback) }}');"></div>
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height:220px;background:#f5f2ef;">
                                    <div style="width:96px;height:96px;border-radius:50%;background:#39322a;display:flex;align-items:center;justify-content:center;">
                                        <span style="color:#dacbbc;font-size:28px;font-weight:700;">{{ strtoupper(substr($member->name,0,1)) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div style="background:#685b4e;padding:22px 20px;">
                            <h5 style="color:#ffffff;margin:0;font-weight:800;font-size:1.25rem;letter-spacing:0.2px;">{{ $member->name }}</h5>
                            @if($member->email)
                                <p style="color:rgba(218,203,188,0.95);margin:6px 0 0;font-size:0.95rem;">{{ $member->email }}</p>
                            @endif
                        </div>

                        <div class="card-body" style="background:#ffffff;padding:20px;">
                            <p class="mb-0" style="color:#6b6b6b;line-height:1.6;">{{ $member->bio ?: translate('No biography added yet.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            @if($team_members->isEmpty())
                <div class="col-12">
                    <div class="alert alert-info mb-0">{{ translate('No team members have been added yet.') }}</div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
