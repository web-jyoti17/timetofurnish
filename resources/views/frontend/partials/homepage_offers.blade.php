@php
    $home_offers = \App\Models\Offer::homeSection()->with('products')->get();
@endphp
@if ($home_offers->count() > 0)
    <style>
        .premium-offer-section {
            background: linear-gradient(135deg, #fdfbf7 0%, #f7f0e3 50%, #eadfc9 100%) !important;
            border: 1px solid rgba(226, 215, 192, 0.5) !important;
            outline: 5px solid rgba(226, 215, 192, 0.18);
            outline-offset: -1px;
            border-radius: 36px;
            box-shadow: 0 25px 60px -25px rgba(103, 93, 76, 0.14), inset 0 1px 0 rgba(255, 255, 255, 0.7);
            min-height: 300px;
            position: relative;
            overflow: hidden;
        }
        .premium-offer-card {
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(253, 250, 245, 0.85) 100%) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 15px 35px -10px rgba(103, 93, 76, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            overflow: hidden;
            padding: 0 !important;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .premium-offer-card:hover {
            transform: translateY(-8px) scale(1.01) !important;
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(253, 250, 245, 0.95) 100%) !important;
            border-color: var(--soft-primary) !important;
            box-shadow: 0 30px 50px -15px rgba(103, 93, 76, 0.18), 0 0 0 2px var(--soft-primary) !important;
        }
        .premium-offer-card:hover img {
            transform: scale(1.05) !important;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        /* Slick carousel custom arrows & dots style */
        .offer-carousel .slick-prev,
        .offer-carousel .slick-next {
            background: #ffffff !important;
            border: 1px solid rgba(226, 215, 192, 0.5) !important;
            box-shadow: 0 8px 20px -5px rgba(103, 93, 76, 0.1) !important;
            color: #0f172a !important;
            width: 46px !important;
            height: 46px !important;
            transition: all 0.3s ease !important;
            border-radius: 50% !important;
            z-index: 10 !important;
        }
        .offer-carousel .slick-prev:hover,
        .offer-carousel .slick-next:hover {
            background: var(--primary) !important;
            color: #ffffff !important;
            border-color: var(--primary) !important;
            box-shadow: 0 12px 25px var(--soft-primary) !important;
        }
        .offer-carousel .slick-prev {
            left: -23px !important;
        }
        .offer-carousel .slick-next {
            right: -23px !important;
        }
        .offer-carousel .slick-dots {
            bottom: -32px !important;
        }
        .offer-carousel .slick-dots li button {
            background: rgba(103, 93, 76, 0.3) !important;
            opacity: 1 !important;
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            transition: all 0.3s ease !important;
        }
        .offer-carousel .slick-dots li.slick-active button {
            background: var(--primary) !important;
            width: 24px !important;
            border-radius: 6px !important;
        }
        @keyframes pulse-theme {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .premium-countdown-timer .seconds {
            color: var(--primary) !important;
            display: inline-block;
            animation: pulse-theme 1s infinite ease-in-out;
        }
    </style>
    <section class="mb-4 mt-5">
        <div class="container">
            <div class="aiz-carousel gutters-16 offer-carousel" data-items="1" data-arrows="true" data-dots="true" data-autoplay="true" data-infinite="true">
                @foreach ($home_offers as $offer)
                    <div class="carousel-box">
                        <div class="premium-offer-section p-4 p-md-5 d-flex flex-wrap align-items-center">
                            
                            <!-- Decorative background soft blurs (Warm luxury ambient glows) -->
                            <div class="position-absolute" style="top: -40px; right: -20px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(226, 156, 9, 0.08) 0%, rgba(226, 156, 9, 0) 70%); filter: blur(40px); border-radius: 50%; pointer-events: none; z-index: 0;"></div>
                            <div class="position-absolute" style="bottom: -50px; left: 5%; width: 320px; height: 320px; background: radial-gradient(circle, var(--soft-primary) 0%, rgba(255,255,255,0) 70%); filter: blur(50px); border-radius: 50%; pointer-events: none; z-index: 0;"></div>
                            <div class="position-absolute" style="top: 20%; right: 30%; width: 220px; height: 220px; background: radial-gradient(circle, rgba(236, 223, 232, 0.6) 0%, rgba(236, 223, 232, 0) 70%); filter: blur(40px); border-radius: 50%; pointer-events: none; z-index: 0;"></div>

                            <!-- Left content -->
                            <div class="col-lg-5 col-12 mb-4 mb-lg-0 z-1 text-left">
                                <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 8px;">
                                    <span class="fs-10 fw-800 text-uppercase tracking-wider px-3 text-white" style="padding: 10px !important;background: #000000 !important; color: #ffffff !important; border-radius: 30px; letter-spacing: 1px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                                        {{ translate($offer->name) }}
                                    </span>
                                    @if($offer->badge_text)
                                        @php
                                            $badge_txt = $offer->badge_text;
                                            if (is_numeric($badge_txt) || (str_ends_with($badge_txt, '%') && !str_contains(strtolower($badge_txt), 'off'))) {
                                                $badge_txt .= ' OFF';
                                            }
                                        @endphp
                                        <span class="fs-10 fw-800 text-uppercase tracking-wider px-2.5 py-1" style="padding:10px;background: rgba(254, 243, 199, 0.85); color: #b45309; border: 1px solid rgba(253, 230, 138, 0.8); border-radius: 6px;">
                                            🔥 {{ $badge_txt }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="fs-28 fs-md-36 fw-800 leading-tight mb-2" style="color: #0f172a !important; font-family: 'Outfit', sans-serif; letter-spacing: -0.8px;">
                                    {{ translate($offer->name) }}
                                </h2>
                                @if($offer->custom_text)
                                    <p class="fs-14 fs-md-15 mb-4" style="color: #475569 !important; max-width: 420px; line-height: 1.6; font-weight: 450;">
                                        {{ $offer->custom_text }}
                                    </p>
                                @endif
                                
                                <!-- Premium countdown timer -->
                                @if($offer->ends_at)
                                    <div class="premium-countdown-timer d-flex align-items-center mb-4" data-end-date="{{ $offer->ends_at->format('Y/m/d H:i:s') }}" style="gap: 8px;">
                                        <div class="d-flex flex-column align-items-center justify-content-center" style="width: 54px; height: 58px; background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 156, 9, 0.25) !important; border-radius: 12px !important; box-shadow: 0 6px 12px -2px rgba(103, 93, 76, 0.05) !important;">
                                            <span class="days fs-18 fw-800 text-dark" style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                            <span class="fs-9 fw-600 text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 8px !important;">Days</span>
                                        </div>
                                        <div class="fs-16 fw-700" style="color: rgba(226, 156, 9, 0.4) !important; line-height: 58px;">:</div>
                                        <div class="d-flex flex-column align-items-center justify-content-center" style="width: 54px; height: 58px; background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 156, 9, 0.25) !important; border-radius: 12px !important; box-shadow: 0 6px 12px -2px rgba(103, 93, 76, 0.05) !important;">
                                            <span class="hours fs-18 fw-800 text-dark" style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                            <span class="fs-9 fw-600 text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 8px !important;">Hours</span>
                                        </div>
                                        <div class="fs-16 fw-700" style="color: rgba(226, 156, 9, 0.4) !important; line-height: 58px;">:</div>
                                        <div class="d-flex flex-column align-items-center justify-content-center" style="width: 54px; height: 58px; background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 156, 9, 0.25) !important; border-radius: 12px !important; box-shadow: 0 6px 12px -2px rgba(103, 93, 76, 0.05) !important;">
                                            <span class="minutes fs-18 fw-800 text-dark" style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                            <span class="fs-9 fw-600 text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 8px !important;">Mins</span>
                                        </div>
                                        <div class="fs-16 fw-700" style="color: rgba(226, 156, 9, 0.4) !important; line-height: 58px;">:</div>
                                        <div class="d-flex flex-column align-items-center justify-content-center" style="width: 54px; height: 58px; background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 156, 9, 0.25) !important; border-radius: 12px !important; box-shadow: 0 6px 12px -2px rgba(103, 93, 76, 0.05) !important;">
                                            <span class="seconds fs-18 fw-800" style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                            <span class="fs-9 fw-600 text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 8px !important;">Secs</span>
                                        </div>
                                    </div>
                                @endif
 
                                @if($offer->products->count() > 0)
                                    <a href="{{ route('product', $offer->products->first()->slug) }}" class="btn px-4 py-2.5 text-white fw-700 fs-13 d-inline-flex align-items-center" 
                                       style="background: #000000 !important; color: #ffffff !important; border-radius: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; gap: 8px;">
                                        <span>{{ translate('Shop Now') }}</span>
                                        <i class="las la-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
 
                            <!-- Right products grid -->
                            <div class="col-lg-7 col-12 z-1">
                                @php
                                    $offer_products = $offer->products->take(3);
                                    $prod_count = $offer_products->count();
                                @endphp
                                <div class="row gutters-10 justify-content-center justify-content-lg-end">
                                    @foreach($offer_products as $product)
                                        <div class="{{ $prod_count == 1 ? 'col-md-6 col-10' : ($prod_count == 2 ? 'col-sm-6 col-12' : 'col-md-4 col-6') }} mb-2">
                                            <div class="premium-offer-card text-center h-100 d-flex flex-column justify-content-between">
                                                <!-- Top Image (Full-bleed cover) -->
                                                <a href="{{ route('product', $product->slug) }}" class="d-block overflow-hidden position-relative" style="height: 230px; display: flex; align-items: center; justify-content: center; background: transparent; border-bottom: 1px solid rgba(226, 215, 192, 0.25); padding: 0 !important;">
                                                    <img src="{{ $product->thumbnail != null ? my_asset($product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}" 
                                                         class="img-fluid lazyload has-transition" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);" 
                                                         alt="{{ $product->getTranslation('name') }}"
                                                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                                <!-- Bottom Info (Padded) -->
                                                <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                                    <h3 class="fs-13 text-truncate-2 mb-3" style="font-weight: 700; line-height: 1.4; min-height: 38px;">
                                                        <a href="{{ route('product', $product->slug) }}" class="text-reset hov-text-primary" style="color: #0f172a; transition: color 0.2s ease;">{{ $product->getTranslation('name') }}</a>
                                                    </h3>
                                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top" style="border-top: 1px solid rgba(226, 215, 192, 0.25) !important;">
                                                        <div class="text-left">
                                                            <span class="d-block fs-10 fw-600 text-muted text-uppercase mb-0.5" style="letter-spacing: 0.5px;">Offer Price</span>
                                                            <span class="d-block fw-800 fs-15" style="color: #e29c09 !important; font-family: 'Outfit', sans-serif;">{{ home_discounted_base_price($product) }}</span>
                                                        </div>
                                                        @php
                                                            $old_price = home_offer_old_price($product);
                                                        @endphp
                                                        @if($old_price)
                                                            <div class="text-right">
                                                                <span class="d-block fs-10 fw-600 text-muted text-uppercase mb-0.5" style="letter-spacing: 0.5px; opacity: 0.7;">Original</span>
                                                                <del class="d-block fs-12 fw-600" style="color: #94a3b8; text-decoration: line-through;">{{ $old_price }}</del>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
 
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const timers = document.querySelectorAll('.premium-countdown-timer');
            timers.forEach(function(timer) {
                const endDateStr = timer.getAttribute('data-end-date');
                if (!endDateStr) return;
                const endDate = new Date(endDateStr).getTime();
                
                const daysVal = timer.querySelector('.days');
                const hoursVal = timer.querySelector('.hours');
                const minsVal = timer.querySelector('.minutes');
                const secsVal = timer.querySelector('.seconds');
                
                function updateTimer() {
                    const now = new Date().getTime();
                    const difference = endDate - now;
                    
                    if (difference <= 0) {
                        if (daysVal) daysVal.innerText = '00';
                        if (hoursVal) hoursVal.innerText = '00';
                        if (minsVal) minsVal.innerText = '00';
                        if (secsVal) secsVal.innerText = '00';
                        return;
                    }
                    
                    const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((difference % (1000 * 60)) / 1000);
                    
                    if (daysVal) daysVal.innerText = String(days).padStart(2, '0');
                    if (hoursVal) hoursVal.innerText = String(hours).padStart(2, '0');
                    if (minsVal) minsVal.innerText = String(minutes).padStart(2, '0');
                    if (secsVal) secsVal.innerText = String(seconds).padStart(2, '0');
                }
                
                updateTimer();
                setInterval(updateTimer, 1000);
            });
        });
    </script>
@endif
