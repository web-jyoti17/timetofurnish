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
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 99 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #ffffff !important;
            border: 1px solid rgba(226, 215, 192, 0.5) !important;
            box-shadow: 0 8px 20px -5px rgba(103, 93, 76, 0.1) !important;
            color: #0f172a !important;
            width: 46px !important;
            height: 46px !important;
            transition: all 0.3s ease !important;
            border-radius: 50% !important;
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

        /* Equal height slides styles */
        .offer-carousel .slick-track {
            display: flex !important;
            align-items: stretch !important;
        }

        .offer-carousel .slick-slide {
            height: auto !important;
            display: flex !important;
        }

        .offer-carousel .slick-slide>div {
            display: flex !important;
            flex: 1 1 auto !important;
            width: 100% !important;
        }

        .offer-carousel .carousel-box {
            display: flex !important;
            flex: 1 1 auto !important;
            width: 100% !important;
        }

        .offer-carousel .premium-offer-section {
            flex: 1 1 auto !important;
            align-self: stretch !important;
        }

        .offer-carousel .slick-dots {
            bottom: -32px !important;
        }

        .offer-carousel .slick-dots li,
        .offer-inner-carousel .slick-dots li {
            width: auto !important;
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

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .premium-countdown-timer .seconds {
            color: var(--primary) !important;
            display: inline-block;
            animation: pulse-theme 1s infinite ease-in-out;
        }

        .offer-copy-layout {
            display: flex !important;
            flex-direction: column !important;
            width: 100%;
        }

        @media (max-width: 991.98px) {

            /* Style 1 Grid layout */
            .premium-offer-section:not(.offer-style-2):not(.offer-style-3) .offer-copy-layout {
                display: grid !important;
                grid-template-columns: 1fr auto !important;
                grid-template-rows: auto auto !important;
                gap: 12px !important;
                width: 100% !important;
            }

            .premium-offer-section:not(.offer-style-2):not(.offer-style-3) .offer-badges-container {
                grid-column: 1 !important;
                grid-row: 1 !important;
                margin-bottom: 0 !important;
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                align-items: center !important;
                gap: 8px !important;
            }

            .premium-offer-section:not(.offer-style-2):not(.offer-style-3) .offer-copy-btn {
                grid-column: 2 !important;
                grid-row: 1 !important;
                margin-top: 0 !important;
                align-self: center !important;
            }

            .premium-offer-section:not(.offer-style-2):not(.offer-style-3) .offer-copy-btn .btn {
                width: auto !important;
                white-space: nowrap !important;
                padding: 8px 12px !important;
                font-size: 11px !important;
            }

            .premium-offer-section:not(.offer-style-2):not(.offer-style-3) .offer-title-desc-timer {
                grid-column: 1 / span 2 !important;
                grid-row: 2 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                width: 100% !important;
            }

            /* Style 2 split layout fallback */
            .premium-offer-section.offer-style-2 .offer-copy-layout {
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: flex-start !important;
            }

            .premium-offer-section.offer-style-2 .offer-copy-info {
                flex: 1 1 auto !important;
                min-width: 0 !important;
                padding-right: 8px !important;
            }

            .premium-offer-section.offer-style-2 .offer-copy-btn {
                flex: 0 0 auto !important;
                margin-top: 6px !important;
            }

            .premium-offer-section.offer-style-2 .offer-copy-btn .btn {
                width: auto !important;
                white-space: nowrap !important;
                padding: 8px 12px !important;
                font-size: 11px !important;
            }
        }

        @media (max-width: 767.98px) {
            .home-offers-wrap {
                margin-top: 18px !important;
                margin-bottom: 26px !important;
                overflow: visible !important;
                margin-top: 50px !important;
            }

            .home-offers-wrap .container {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }

            .premium-offer-section {
                border-radius: 20px !important;
                min-height: 0 !important;
                padding: 18px 12px 34px !important;
                outline-width: 3px;
                box-shadow: 0 18px 42px -24px rgba(103, 93, 76, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.75) !important;
            }

            .premium-offer-card:hover {
                transform: none !important;
            }

            .offer-copy-panel {
                order: 1;
                margin-bottom: 18px !important;
                text-align: left !important;
                padding-left: 6px !important;
                padding-right: 6px !important;
            }

            /* Slick arrows custom layout on mobile */
            .offer-carousel .slick-prev,
            .offer-carousel .slick-next {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 38px !important;
                height: 38px !important;
                font-size: 16px !important;
            }

            .offer-carousel .slick-prev {
                left: 4px !important;
            }

            .offer-carousel .slick-next {
                right: 4px !important;
            }

            .offer-products-panel {
                order: 2;
                padding-left: 6px !important;
                padding-right: 6px !important;
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                overflow: visible !important;
            }

            .offer-copy-panel h2 {
                font-size: 25px !important;
                line-height: 1.2 !important;
                letter-spacing: 0 !important;
                margin-bottom: 9px !important;
            }

            .offer-copy-panel p {
                font-size: 14px !important;
                line-height: 1.45 !important;
                max-width: none !important;
                margin-bottom: 16px !important;
            }

            .offer-copy-panel .fw-800.text-uppercase {
                font-size: 11px !important;
                padding: 9px 13px !important;
                letter-spacing: 0 !important;
            }

            .premium-countdown-timer {
                gap: 4px !important;
                margin-bottom: 12px !important;
                max-width: 100% !important;
                flex-wrap: nowrap !important;
            }

            .premium-countdown-timer>div:not(.fs-16) {
                width: 36px !important;
                height: 42px !important;
                border-radius: 6px !important;
            }

            .premium-countdown-timer .days,
            .premium-countdown-timer .hours,
            .premium-countdown-timer .minutes,
            .premium-countdown-timer .seconds {
                font-size: 13px !important;
            }

            .premium-countdown-timer .fs-9 {
                font-size: 7px !important;
            }

            .premium-countdown-timer .fs-16 {
                font-size: 9px !important;
                line-height: 42px !important;
            }

            .offer-copy-panel .btn {
                padding: 8px 12px !important;
                font-size: 11px !important;
                line-height: 1.2 !important;
                min-height: 34px !important;
                gap: 6px !important;
            }

            .premium-offer-section .btn,
            .offer-dark-panel .btn,
            .premium-offer-section.offer-style-3 .btn {
                padding: 8px 12px !important;
                font-size: 11px !important;
                line-height: 1.2 !important;
                min-height: 34px !important;
            }

            .offer-inner-carousel {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .offer-inner-carousel .slick-list {
                margin-left: 0 !important;
                margin-right: 0 !important;
                overflow: hidden !important;
                width: 100% !important;
                padding: 0 !important;
            }

            .offer-inner-carousel .slick-track {
                padding-top: 0 !important;
            }

            .offer-inner-carousel .slick-track,
            .offer-inner-carousel .slick-slide,
            .offer-inner-carousel .slick-slide>div,
            .offer-inner-carousel .carousel-box {
                display: flex !important;
                align-items: stretch !important;
            }

            .offer-inner-carousel .carousel-box {
                padding-left: 6px !important;
                padding-right: 6px !important;
                height: auto !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .offer-inner-carousel .modern-product-card {
                border-radius: 12px !important;
                padding: 7px !important;
                height: 100% !important;
                min-height: 180px !important;
                justify-content: space-between !important;
                min-width: 0 !important;
                box-shadow: 0 7px 20px rgba(104, 91, 78, 0.07) !important;
            }

            .offer-inner-carousel .modern-product-img-wrap {
                height: 98px !important;
                border-radius: 10px !important;
            }

            .offer-inner-carousel .modern-product-card>div:last-child {
                padding: 7px 2px 2px !important;
                flex-grow: 1 !important;
                justify-content: space-between !important;
            }

            .offer-inner-carousel .modern-product-title {
                min-height: 0 !important;
                margin-bottom: 8px !important;
            }

            .offer-inner-carousel .modern-product-title a {
                display: block !important;
                font-size: 11px !important;
                line-height: 1.3 !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                -webkit-line-clamp: unset !important;
                -webkit-box-orient: initial !important;
            }

            .offer-inner-carousel .modern-card-bottom-row {
                padding-top: 6px !important;
                margin-top: 0px !important;
                min-height: 28px !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 5px !important;
            }

            .offer-inner-carousel .modern-price-wrap,
            .offer-inner-carousel .modern-price-wrap .text-primary,
            .offer-inner-carousel .modern-price-wrap span {
                font-size: 9.5px !important;
                line-height: 1.35 !important;
                min-width: 0 !important;
            }

            .offer-inner-carousel .modern-price-wrap {
                width: auto !important;
                flex: 1 1 auto !important;
                justify-content: flex-start !important;
                text-align: left !important;
            }

            .offer-inner-carousel .modern-price-wrap del {
                font-size: 9px !important;
                width: 100%;
            }

            .offer-inner-carousel .modern-card-actions-bottom {
                width: auto !important;
                flex: 0 0 13% !important;
                flex-direction: column;
                gap: 0 !important;
            }

            .offer-inner-carousel .modern-card-actions-bottom .modern-action-btn {
                width: 18px !important;
                height: 18px !important;
            }

            .offer-inner-carousel .modern-action-btn svg {
                width: 14px !important;
                height: 14px !important;
            }

            .offer-single-product-carousel .slick-track {
                justify-content: center !important;
                margin-left: auto !important;
                margin-right: auto !important;
                transform: none !important;
            }

            .offer-inner-carousel .modern-discount-tag,
            .offer-inner-carousel .modern-wholesale-tag {
                font-size: 8px !important;
                padding: 4px 7px !important;
                margin-left: 6px !important;
                margin-top: 6px !important;
            }

            .offer-carousel .slick-dots {
                bottom: -25px !important;
            }

            .offer-inner-carousel .slick-dots {
                bottom: -22px !important;
            }

            .offer-carousel .slick-dots,
            .offer-inner-carousel .slick-dots {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px !important;
                margin: 0 !important;
                padding: 0 !important;
                z-index: 10 !important;
                pointer-events: auto !important;
            }

            .offer-carousel .slick-dots li,
            .offer-inner-carousel .slick-dots li {
                width: auto !important;
                height: 8px !important;
                margin: 0 !important;
                position: relative !important;
                z-index: 10 !important;
                pointer-events: auto !important;
            }

            .offer-carousel .slick-dots li button,
            .offer-inner-carousel .slick-dots li button {
                width: 7px !important;
                height: 7px !important;
                padding: 0 !important;
                border-radius: 999px !important;
                background: #d7c8b5 !important;
                opacity: 1 !important;
                transition: width 0.25s ease, background-color 0.25s ease !important;
                position: relative !important;
                pointer-events: auto !important;
                cursor: pointer !important;
            }

            .offer-carousel .slick-dots li button::after,
            .offer-inner-carousel .slick-dots li button::after {
                content: "" !important;
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 24px !important;
                height: 24px !important;
                background: transparent !important;
                display: block !important;
                z-index: 12 !important;
                pointer-events: auto !important;
            }

            .offer-carousel .slick-dots li.slick-active button,
            .offer-inner-carousel .slick-dots li.slick-active button {
                width: 24px !important;
                background: #685b4e !important;
                box-shadow: 0 4px 10px rgba(104, 91, 78, 0.24) !important;
            }
        }

        @media (max-width: 480px) {
            .premium-countdown-timer {
                gap: 3px !important;
            }

            .premium-countdown-timer>div:not(.fs-16) {
                width: 32px !important;
                height: 38px !important;
                border-radius: 6px !important;
            }

            .premium-countdown-timer .days,
            .premium-countdown-timer .hours,
            .premium-countdown-timer .minutes,
            .premium-countdown-timer .seconds {
                font-size: 12px !important;
            }

            .premium-countdown-timer .fs-9 {
                font-size: 6px !important;
            }

            .premium-countdown-timer .fs-16 {
                font-size: 8px !important;
                line-height: 38px !important;
            }
        }

        /* Style 2 Styles */
        .premium-offer-section.offer-style-2 {
            background: linear-gradient(135deg, #fcfbf9 0%, #f6eee0 100%) !important;
            border: 1px solid rgba(226, 215, 192, 0.5) !important;
            border-radius: 32px !important;
            box-shadow: 0 25px 60px -25px rgba(103, 93, 76, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
            min-height: auto !important;
        }

        .offer-dark-panel {
            background: linear-gradient(135deg, #51463a 0%, #685b4e 100%) !important;
            border-radius: 24px !important;
            box-shadow: 0 15px 35px rgba(81, 70, 58, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        /* Style 3 Styles */
        .premium-offer-section.offer-style-3 {
            background: #fafaf8 !important;
            border: 4px double #685b4e !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 45px -20px rgba(103, 93, 76, 0.1) !important;
            min-height: auto !important;
        }

        .premium-offer-section.offer-style-3 .offer-inner-carousel .modern-product-card {
            border: 1px solid #f0ebe2 !important;
            box-shadow: 0 8px 24px rgba(104, 91, 78, 0.04) !important;
        }

        .premium-offer-section.offer-style-3 .offer-inner-carousel .modern-product-card:hover {
            box-shadow: 0 15px 35px rgba(104, 91, 78, 0.1) !important;
            border-color: #685b4e !important;
        }

        @media (max-width: 767.98px) {
            .offer-dark-panel {
                padding: 1.5rem !important;
                min-height: auto !important;
                margin-bottom: 24px !important;
            }

            .premium-offer-section.offer-style-3 {
                padding: 24px 16px !important;
            }
        }

        /* Theme-only premium redesign */
        .home-offers-wrap {
            --primary: #685b4e;
            --hov-primary: #4f4238;
            --soft-primary: rgba(104, 91, 78, 0.12);
            --secondary-base: #d8c8b7;
            --soft-secondary-base: #f4eee8;
            --soft-light: #fffdf9;
            --soft-dark: rgba(104, 91, 78, 0.1);
            --dark: #4f4238;
            --secondary: #8c8177;
            --white: #ffffff;
            margin-top: 64px !important;
            margin-bottom: 56px !important;
        }

        .home-offers-wrap .container {
            max-width: 1320px;
        }

        .home-offers-wrap .premium-offer-section,
        .home-offers-wrap .premium-offer-section.offer-style-2,
        .home-offers-wrap .premium-offer-section.offer-style-3 {
            background:
                linear-gradient(135deg, var(--soft-primary) 0%, var(--soft-light) 48%, var(--soft-secondary-base) 100%) !important;
            border: 1px solid var(--soft-primary) !important;
            outline: 1px solid var(--soft-secondary-base) !important;
            outline-offset: -8px;
            border-radius: 28px !important;
            box-shadow: 0 24px 70px var(--soft-dark) !important;
            padding: 32px !important;
            isolation: isolate;
        }

        .home-offers-wrap .premium-offer-section::before {
            content: "";
            position: absolute;
            inset: 12px;
            border: 1px solid var(--soft-secondary-base);
            border-radius: 22px;
            pointer-events: none;
            z-index: 0;
        }

        .home-offers-wrap .premium-offer-section::after {
            content: "";
            position: absolute;
            width: 42%;
            height: 100%;
            top: 0;
            right: 0;
            background: linear-gradient(135deg, transparent 0%, var(--soft-primary) 100%);
            pointer-events: none;
            z-index: 0;
        }

        .home-offers-wrap .offer-dark-panel {
            background: linear-gradient(135deg, var(--primary) 0%, var(--hov-primary) 100%) !important;
            border: 1px solid var(--soft-secondary-base) !important;
            border-radius: 22px !important;
            box-shadow: 0 22px 48px var(--soft-primary) !important;
        }

        .home-offers-wrap .offer-copy-panel,
        .home-offers-wrap .offer-products-panel,
        .home-offers-wrap .offer-dark-panel,
        .home-offers-wrap .premium-offer-section>.z-1,
        .home-offers-wrap .premium-offer-section>.col-12 {
            position: relative;
            z-index: 1;
        }

        .home-offers-wrap .offer-copy-panel h2,
        .home-offers-wrap .premium-offer-section.offer-style-3 h2,
        .home-offers-wrap .offer-dark-panel h2 {
            color: var(--dark) !important;
            font-size: clamp(24px, 3vw, 42px) !important;
            line-height: 1.08 !important;
            letter-spacing: 0 !important;
            margin-bottom: 14px !important;
        }

        .home-offers-wrap .offer-dark-panel h2,
        .home-offers-wrap .offer-dark-panel p,
        .home-offers-wrap .offer-dark-panel span {
            color: var(--white) !important;
        }

        .home-offers-wrap .offer-copy-panel p,
        .home-offers-wrap .premium-offer-section.offer-style-3 p {
            color: var(--secondary) !important;
            font-size: 15px !important;
            line-height: 1.65 !important;
            max-width: 480px !important;
            margin-bottom: 20px !important;
        }

        .home-offers-wrap .offer-kicker,
        .home-offers-wrap .offer-badge {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 8px 13px !important;
            border-radius: 999px !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0 !important;
            line-height: 1 !important;
        }

        .home-offers-wrap .offer-kicker {
            background: var(--primary) !important;
            border: 1px solid var(--primary) !important;
            color: var(--white) !important;
            box-shadow: 0 10px 24px var(--soft-primary) !important;
        }

        .home-offers-wrap .offer-badge {
            background: var(--soft-secondary-base) !important;
            border: 1px solid var(--secondary-base) !important;
            color: var(--dark) !important;
        }

        .home-offers-wrap .offer-dark-panel .offer-kicker,
        .home-offers-wrap .offer-dark-panel .offer-badge {
            background: var(--soft-primary) !important;
            border-color: var(--soft-secondary-base) !important;
            color: var(--white) !important;
            box-shadow: none !important;
        }

        .home-offers-wrap .premium-countdown-timer {
            gap: 8px !important;
            flex-wrap: wrap !important;
        }

        .home-offers-wrap .premium-countdown-timer>div:not(.fs-16),
        .home-offers-wrap .premium-countdown-timer>span.days,
        .home-offers-wrap .premium-countdown-timer>span.hours,
        .home-offers-wrap .premium-countdown-timer>span.minutes,
        .home-offers-wrap .premium-countdown-timer>span.seconds {
            background: var(--white) !important;
            border: 1px solid var(--soft-primary) !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 20px var(--soft-primary) !important;
            color: var(--dark) !important;
        }

        .home-offers-wrap .offer-dark-panel .premium-countdown-timer>div:not(.fs-16) {
            background: var(--soft-primary) !important;
            border-color: var(--soft-secondary-base) !important;
            box-shadow: none !important;
        }

        .home-offers-wrap .premium-countdown-timer .days,
        .home-offers-wrap .premium-countdown-timer .hours,
        .home-offers-wrap .premium-countdown-timer .minutes,
        .home-offers-wrap .premium-countdown-timer .seconds {
            color: var(--primary) !important;
        }

        .home-offers-wrap .offer-dark-panel .premium-countdown-timer .days,
        .home-offers-wrap .offer-dark-panel .premium-countdown-timer .hours,
        .home-offers-wrap .offer-dark-panel .premium-countdown-timer .minutes,
        .home-offers-wrap .offer-dark-panel .premium-countdown-timer .seconds {
            color: var(--white) !important;
        }

        .home-offers-wrap .offer-copy-btn .btn,
        .home-offers-wrap .premium-offer-section.offer-style-3 .btn {
            background: var(--primary) !important;
            border: 1px solid var(--primary) !important;
            color: var(--white) !important;
            border-radius: 999px !important;
            min-height: 42px !important;
            padding: 10px 18px !important;
            box-shadow: 0 14px 28px var(--soft-primary) !important;
        }

        .home-offers-wrap .offer-copy-btn .btn:hover,
        .home-offers-wrap .premium-offer-section.offer-style-3 .btn:hover {
            background: var(--hov-primary) !important;
            border-color: var(--hov-primary) !important;
            transform: translateY(-2px);
        }

        .home-offers-wrap .offer-inner-carousel .carousel-box {
            padding: 8px !important;
        }

        .home-offers-wrap .offer-inner-carousel .modern-product-card {
            height: 100% !important;
            border: 1px solid var(--soft-primary) !important;
            border-radius: 18px !important;
            box-shadow: 0 12px 30px var(--soft-primary) !important;
            background: var(--white) !important;
        }

        .home-offers-wrap .offer-inner-carousel .modern-product-card:hover {
            transform: translateY(-5px) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 20px 42px var(--soft-primary) !important;
        }

        .home-offers-wrap .offer-carousel .slick-prev,
        .home-offers-wrap .offer-carousel .slick-next {
            background: var(--white) !important;
            border-color: var(--soft-primary) !important;
            color: var(--primary) !important;
            box-shadow: 0 14px 30px var(--soft-primary) !important;
        }

        .home-offers-wrap .offer-carousel .slick-prev:hover,
        .home-offers-wrap .offer-carousel .slick-next:hover {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: var(--white) !important;
        }

        .home-offers-wrap .offer-carousel .slick-dots li button,
        .home-offers-wrap .offer-inner-carousel .slick-dots li button {
            background: var(--soft-primary) !important;
        }

        .home-offers-wrap .offer-carousel .slick-dots li.slick-active button,
        .home-offers-wrap .offer-inner-carousel .slick-dots li.slick-active button {
            background: var(--primary) !important;
        }

        .home-offers-wrap .offer-decor {
            background: radial-gradient(circle, var(--soft-primary) 0%, transparent 72%) !important;
        }

        @media (max-width: 991.98px) {
            .home-offers-wrap {
                margin-top: 42px !important;
                margin-bottom: 42px !important;
            }

            .home-offers-wrap .premium-offer-section,
            .home-offers-wrap .premium-offer-section.offer-style-2,
            .home-offers-wrap .premium-offer-section.offer-style-3 {
                padding: 24px !important;
                border-radius: 22px !important;
            }

            .home-offers-wrap .offer-dark-panel {
                min-height: auto !important;
            }

            .home-offers-wrap .offer-products-panel {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }

        @media (max-width: 767.98px) {
            .home-offers-wrap {
                margin-top: 30px !important;
                margin-bottom: 34px !important;
            }

            .home-offers-wrap .container {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .home-offers-wrap .premium-offer-section,
            .home-offers-wrap .premium-offer-section.offer-style-2,
            .home-offers-wrap .premium-offer-section.offer-style-3 {
                padding: 16px 12px 28px !important;
                border-radius: 18px !important;
                outline-offset: -5px;
            }

            .home-offers-wrap .premium-offer-section::before {
                inset: 7px;
                border-radius: 14px;
            }

            .home-offers-wrap .premium-offer-section::after {
                width: 100%;
                height: 42%;
                top: auto;
                bottom: 0;
            }

            .home-offers-wrap .offer-copy-panel {
                margin-bottom: 18px !important;
                padding-left: 6px !important;
                padding-right: 6px !important;
            }

            .home-offers-wrap .offer-copy-layout,
            .home-offers-wrap .premium-offer-section:not(.offer-style-2):not(.offer-style-3) .offer-copy-layout,
            .home-offers-wrap .premium-offer-section.offer-style-2 .offer-copy-layout {
                display: flex !important;
                flex-direction: column !important;
                gap: 12px !important;
            }

            .home-offers-wrap .offer-badges-container {
                gap: 6px !important;
                margin-bottom: 2px !important;
            }

            .home-offers-wrap .offer-copy-panel h2,
            .home-offers-wrap .premium-offer-section.offer-style-3 h2,
            .home-offers-wrap .offer-dark-panel h2 {
                font-size: 24px !important;
                line-height: 1.14 !important;
                margin-bottom: 8px !important;
            }

            .home-offers-wrap .offer-copy-panel p,
            .home-offers-wrap .premium-offer-section.offer-style-3 p {
                font-size: 13px !important;
                line-height: 1.5 !important;
                margin-bottom: 12px !important;
            }

            .home-offers-wrap .offer-copy-btn,
            .home-offers-wrap .premium-offer-section:not(.offer-style-2):not(.offer-style-3) .offer-copy-btn,
            .home-offers-wrap .premium-offer-section.offer-style-2 .offer-copy-btn {
                align-self: stretch !important;
                margin-top: 0 !important;
            }

            .home-offers-wrap .offer-copy-btn .btn,
            .home-offers-wrap .premium-offer-section.offer-style-3 .btn {
                width: 100% !important;
                min-height: 40px !important;
            }

            .home-offers-wrap .premium-countdown-timer {
                gap: 5px !important;
                margin-bottom: 12px !important;
            }

            .home-offers-wrap .premium-countdown-timer>div:not(.fs-16) {
                width: 39px !important;
                height: 44px !important;
            }

            .home-offers-wrap .offer-inner-carousel .carousel-box {
                padding-left: 5px !important;
                padding-right: 5px !important;
            }

            .home-offers-wrap .offer-inner-carousel .modern-product-card {
                border-radius: 14px !important;
                min-height: 188px !important;
            }
        }

        /* Final mobile polish using the site theme palette */
        .home-offers-wrap .premium-offer-section,
        .home-offers-wrap .premium-offer-section.offer-style-2,
        .home-offers-wrap .premium-offer-section.offer-style-3 {
            background: linear-gradient(135deg, #fffdf9 0%, #f4eee8 100%) !important;
            border: 1.5px solid #d8c8b7 !important;
            outline: 0 !important;
            box-shadow: 0 18px 44px rgba(104, 91, 78, 0.1) !important;
        }

        .home-offers-wrap .premium-offer-section::before,
        .home-offers-wrap .premium-offer-section::after {
            content: none !important;
        }

        .home-offers-wrap .offer-copy-panel h2,
        .home-offers-wrap .premium-offer-section.offer-style-3 h2,
        .home-offers-wrap .offer-dark-panel h2 {
            color: #4f4238 !important;
            letter-spacing: 0 !important;
        }

        .home-offers-wrap .offer-copy-panel p,
        .home-offers-wrap .premium-offer-section.offer-style-3 p,
        .home-offers-wrap .offer-dark-panel p {
            color: #8c8177 !important;
        }

        .home-offers-wrap .offer-kicker,
        .home-offers-wrap .offer-dark-panel .offer-kicker {
            background: #685b4e !important;
            border-color: #685b4e !important;
            color: #ffffff !important;
            box-shadow: 0 10px 22px rgba(104, 91, 78, 0.14) !important;
        }

        .home-offers-wrap .offer-badge,
        .home-offers-wrap .offer-dark-panel .offer-badge {
            background: #ffffff !important;
            border-color: #d8c8b7 !important;
            color: #685b4e !important;
        }

        .home-offers-wrap .offer-copy-btn .btn,
        .home-offers-wrap .premium-offer-section.offer-style-3 .btn {
            background: #685b4e !important;
            border-color: #685b4e !important;
            color: #ffffff !important;
            box-shadow: 0 12px 24px rgba(104, 91, 78, 0.16) !important;
        }

        .home-offers-wrap .premium-countdown-timer>div:not(.fs-16) {
            background: #ffffff !important;
            border: 1px solid #d8c8b7 !important;
            box-shadow: 0 8px 18px rgba(104, 91, 78, 0.07) !important;
        }

        .home-offers-wrap .premium-countdown-timer .days,
        .home-offers-wrap .premium-countdown-timer .hours,
        .home-offers-wrap .premium-countdown-timer .minutes,
        .home-offers-wrap .premium-countdown-timer .seconds {
            color: #4f4238 !important;
        }

        .home-offers-wrap .premium-countdown-timer .fs-9,
        .home-offers-wrap .premium-countdown-timer .text-muted,
        .home-offers-wrap .premium-countdown-timer .text-white-50 {
            color: #8c8177 !important;
            opacity: 1 !important;
        }

        .home-offers-wrap .offer-products-panel,
        .home-offers-wrap .offer-inner-carousel,
        .home-offers-wrap .offer-inner-carousel .slick-list,
        .home-offers-wrap .offer-inner-carousel .slick-track,
        .home-offers-wrap .offer-inner-carousel .slick-slide,
        .home-offers-wrap .offer-inner-carousel .slick-slide>div,
        .home-offers-wrap .offer-inner-carousel .carousel-box {
            background: transparent !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }

        .home-offers-wrap .offer-inner-carousel {
            padding: 0 !important;
        }

        .home-offers-wrap .offer-inner-carousel .slick-list {
            margin-bottom: 0 !important;
        }

        .home-offers-wrap .offer-inner-carousel .modern-product-card {
            background: #ffffff !important;
        }

        @media (max-width: 767.98px) {

            .home-offers-wrap .premium-offer-section,
            .home-offers-wrap .premium-offer-section.offer-style-2,
            .home-offers-wrap .premium-offer-section.offer-style-3 {
                padding: 18px 12px 28px !important;
                border-radius: 18px !important;
            }

            .home-offers-wrap .offer-copy-panel,
            .home-offers-wrap .offer-dark-panel {
                padding-left: 6px !important;
                padding-right: 6px !important;
                margin-bottom: 16px !important;
                background: transparent !important;
                border: 0 !important;
                box-shadow: none !important;
            }

            .home-offers-wrap .offer-copy-panel h2,
            .home-offers-wrap .premium-offer-section.offer-style-3 h2,
            .home-offers-wrap .offer-dark-panel h2 {
                font-size: 25px !important;
                line-height: 1.16 !important;
            }

            .home-offers-wrap .offer-copy-btn .btn,
            .home-offers-wrap .premium-offer-section.offer-style-3 .btn {
                width: 100% !important;
                justify-content: center !important;
            }

            .home-offers-wrap .offer-products-panel {
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-top: 4px !important;
            }

            .home-offers-wrap .offer-inner-carousel .carousel-box {
                padding-left: 4px !important;
                padding-right: 4px !important;
            }
        }
    </style>
    <section class="mb-4 mt-5 home-offers-wrap">
        <div class="container">
            <div class="offer-carousel gutters-16" data-items="1" data-arrows="true" data-dots="false" data-autoplay="false"
                data-infinite="true">
                @foreach ($home_offers as $offer)
                    @php
                        $style = $offer->template_style ?? 'style_1';
                        $offerProductCount = $offer->products->count();
                        $offerProductCarouselClass = $offerProductCount === 1 ? ' offer-single-product-carousel' : '';
                    @endphp
                    <div class="carousel-box">
                        @if ($style === 'style_2')
                            <!-- Render Style 2: Dark Warm Split -->
                            <div
                                class="premium-offer-section offer-style-2 p-4 d-flex flex-wrap align-items-center position-relative overflow-hidden">
                                <!-- Decorative background soft blurs (Warm luxury ambient glows) -->
                                <div class="position-absolute"
                                    style="top: -40px; right: -20px; width: 280px; height: 280px; background: radial-gradient(circle, var(--soft-primary) 0%, transparent 70%); filter: blur(40px); border-radius: 50%; pointer-events: none; z-index: 0;">
                                </div>

                                <!-- Left content (Dark container block) -->
                                <div class="col-lg-5 col-12 mb-4 mb-lg-0 z-1 text-left offer-dark-panel p-4 p-md-5 d-flex flex-column justify-content-between"
                                    style="min-height: 380px;">
                                    <div
                                        class="offer-copy-layout d-flex flex-column w-100 h-100 justify-content-between">
                                        <div class="offer-copy-info">
                                            <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 8px;">
                                                <span
                                                    class="fs-10 fw-800 text-uppercase tracking-wider px-3 text-white offer-kicker"
                                                    style="padding: 8px 14px !important; border-radius: 30px; letter-spacing: 1px;">
                                                    {{ translate($offer->name) }}
                                                </span>
                                                @if ($offer->badge_text)
                                                    @php
                                                        $badge_txt = $offer->badge_text;
                                                        if (
                                                            is_numeric($badge_txt) ||
                                                            (str_ends_with($badge_txt, '%') &&
                                                                !str_contains(strtolower($badge_txt), 'off'))
                                                        ) {
                                                            $badge_txt .= ' OFF';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="fs-10 fw-800 text-uppercase tracking-wider px-2.5 py-1 offer-badge"
                                                        style="padding: 8px 12px !important; border-radius: 6px;">
                                                        {{ $badge_txt }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h2 class="fs-28 fs-md-36 fw-800 leading-tight mb-3 text-white"
                                                style="font-family: 'Outfit', sans-serif; letter-spacing: -0.8px;">
                                                {{ translate($offer->name) }}
                                            </h2>
                                            @if ($offer->custom_text)
                                                <p class="fs-14 fs-md-15 mb-4"
                                                    style="line-height: 1.6; font-weight: 400; max-width: none;">
                                                    {{ $offer->custom_text }}
                                                </p>
                                            @endif
                                            <!-- Premium countdown timer -->
                                            @if ($offer->ends_at)
                                                <div class="premium-countdown-timer d-flex align-items-center mb-4"
                                                    data-end-date="{{ $offer->ends_at->format('Y/m/d H:i:s') }}"
                                                    style="gap: 8px;">
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 52px; height: 56px; border-radius: 12px !important;">
                                                        <span class="days fs-18 fw-800 text-white"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-white-50 text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important; opacity:0.75;">Days</span>
                                                    </div>
                                                    <div class="fs-16 fw-700 text-white"
                                                        style="opacity: 0.4; line-height: 56px;">:</div>
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 52px; height: 56px; border-radius: 12px !important;">
                                                        <span class="hours fs-18 fw-800 text-white"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-white-50 text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important; opacity:0.75;">Hours</span>
                                                    </div>
                                                    <div class="fs-16 fw-700 text-white"
                                                        style="opacity: 0.4; line-height: 56px;">:</div>
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 52px; height: 56px; border-radius: 12px !important;">
                                                        <span class="minutes fs-18 fw-800 text-white"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-white-50 text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important; opacity:0.75;">Mins</span>
                                                    </div>
                                                    <div class="fs-16 fw-700 text-white"
                                                        style="opacity: 0.4; line-height: 56px;">:</div>
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 52px; height: 56px; border-radius: 12px !important;">
                                                        <span class="seconds fs-18 fw-800 text-white"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-white-50 text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important; opacity:0.75;">Secs</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($offer->products->count() > 0)
                                            <div class="offer-copy-btn">
                                                <a href="{{ route('product', $offer->products->first()->slug) }}"
                                                    class="btn px-4 py-2.5 text-primary fw-700 fs-13 d-inline-flex align-items-center justify-content-center w-100"
                                                    style="border-radius: 30px; transition: all 0.3s ease; gap: 8px;">
                                                    <span>{{ translate('Shop Now') }}</span>
                                                    <i class="las la-arrow-right"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right products grid using premium carousel and unified template -->
                                <div class="col-lg-7 col-12 z-1 offer-products-panel pl-lg-4">
                                    <div class="aiz-carousel sm-gutters-16 offer-inner-carousel arrow-none{{ $offerProductCarouselClass }}"
                                        data-items="2" data-xl-items="2" data-lg-items="2" data-md-items="2"
                                        data-sm-items="2" data-xs-items="2" data-arrows="false"
                                        data-dots="{{ $offerProductCount > 3 ? 'true' : 'false' }}"
                                        data-infinite="false" data-autoplay="false">
                                        @foreach ($offer->products as $product)
                                            <div class="carousel-box p-1">
                                                @include(
                                                    'frontend.' .
                                                        get_setting('homepage_select') .
                                                        '.partials.product_box_1',
                                                    ['product' => $product]
                                                )
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @elseif($style === 'style_3')
                            <!-- Render Style 3: Minimalist Outline Frame -->
                            <div
                                class="premium-offer-section offer-style-3 p-4 p-md-5 d-flex flex-column align-items-center position-relative overflow-hidden">

                                <!-- Top Centered Header -->
                                <div class="col-12 text-center mb-4 z-1">
                                    <div class="d-flex justify-content-center align-items-center mb-2"
                                        style="gap: 8px;">
                                        <span class="fs-10 fw-800 text-uppercase tracking-wider px-3 offer-kicker"
                                            style="padding: 6px 12px !important; border-radius: 4px; letter-spacing: 1px;">
                                            {{ translate($offer->name) }}
                                        </span>
                                        @if ($offer->badge_text)
                                            @php
                                                $badge_txt = $offer->badge_text;
                                                if (
                                                    is_numeric($badge_txt) ||
                                                    (str_ends_with($badge_txt, '%') &&
                                                        !str_contains(strtolower($badge_txt), 'off'))
                                                ) {
                                                    $badge_txt .= ' OFF';
                                                }
                                            @endphp
                                            <span
                                                class="fs-10 fw-800 text-uppercase tracking-wider px-2 py-1 offer-badge"
                                                style="padding: 6px 10px !important; border-radius: 4px;">
                                                {{ $badge_txt }}
                                            </span>
                                        @endif
                                    </div>

                                    <h2 class="fs-28 fs-md-36 fw-800 leading-tight mb-2"
                                        style="font-family: 'Playfair Display', 'Outfit', serif; letter-spacing: -0.5px;">
                                        {{ translate($offer->name) }}
                                    </h2>

                                    @if ($offer->custom_text)
                                        <p class="fs-14 fs-md-15 mb-4 mx-auto"
                                            style="max-width: 600px; line-height: 1.6; font-style: italic;">
                                            &ldquo;{{ $offer->custom_text }}&rdquo;
                                        </p>
                                    @endif

                                    <!-- Minimal Centered Countdown -->
                                    @if ($offer->ends_at)
                                        <div class="premium-countdown-timer d-inline-flex align-items-center justify-content-center py-2 px-4 mb-2"
                                            data-end-date="{{ $offer->ends_at->format('Y/m/d H:i:s') }}"
                                            style="gap: 12px; border-top: 1px solid var(--soft-primary); border-bottom: 1px solid var(--soft-primary); font-family: 'Outfit', sans-serif;">
                                            <span class="text-uppercase fs-10 fw-700 tracking-wider text-muted-theme"
                                                style="letter-spacing: 1px;">Ends In:</span>
                                            <span class="days fs-14 fw-800 text-dark">00</span><span
                                                class="fs-10 text-muted-theme"
                                                style="margin-left:-6px; font-weight:700;">D</span>
                                            <span class="hours fs-14 fw-800 text-dark">00</span><span
                                                class="fs-10 text-muted-theme"
                                                style="margin-left:-6px; font-weight:700;">H</span>
                                            <span class="minutes fs-14 fw-800 text-dark">00</span><span
                                                class="fs-10 text-muted-theme"
                                                style="margin-left:-6px; font-weight:700;">M</span>
                                            <span class="seconds fs-14 fw-800 text-primary">00</span><span
                                                class="fs-10 text-muted-theme"
                                                style="margin-left:-6px; font-weight:700;">S</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bottom Products Row -->
                                <div class="col-12 z-1 offer-products-panel">
                                    <div class="aiz-carousel sm-gutters-16 offer-inner-carousel arrow-none{{ $offerProductCarouselClass }}"
                                        data-items="3" data-xl-items="3" data-lg-items="3" data-md-items="2"
                                        data-sm-items="2" data-xs-items="2" data-arrows="false"
                                        data-dots="{{ $offerProductCount > 3 ? 'true' : 'false' }}"
                                        data-infinite="false" data-autoplay="false">
                                        @foreach ($offer->products as $product)
                                            <div class="carousel-box p-1">
                                                @include(
                                                    'frontend.' .
                                                        get_setting('homepage_select') .
                                                        '.partials.product_box_1',
                                                    ['product' => $product]
                                                )
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($offer->products->count() > 0)
                                    <div class="text-center mt-4 z-1 w-100">
                                        <a href="{{ route('product', $offer->products->first()->slug) }}"
                                            class="btn px-5 py-2.5 text-primary fw-700 fs-13 d-inline-flex align-items-center"
                                            style="border-radius: 4px; transition: all 0.3s ease; gap: 8px;">
                                            <span>{{ translate('Discover Collection') }}</span>
                                            <i class="las la-arrow-right"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Render Style 1: Premium Glassmorphism (Default) -->
                            <div
                                class="premium-offer-section p-4 p-md-5 d-flex flex-wrap align-items-center position-relative overflow-hidden">

                                <!-- Decorative background soft blurs (Warm luxury ambient glows) -->
                                {{-- <div class="position-absolute"
                                    style="top: -40px; right: -20px; width: 280px; height: 280px; background: radial-gradient(circle, var(--soft-primary) 0%, transparent 70%); filter: blur(40px); border-radius: 50%; pointer-events: none; z-index: 0;">
                                </div>
                                <div class="position-absolute"
                                    style="bottom: -50px; left: 5%; width: 320px; height: 320px; background: radial-gradient(circle, var(--soft-primary) 0%, transparent 70%); filter: blur(50px); border-radius: 50%; pointer-events: none; z-index: 0;">
                                </div>
                                <div class="position-absolute"
                                    style="top: 20%; right: 30%; width: 220px; height: 220px; background: radial-gradient(circle, var(--soft-secondary-base) 0%, transparent 70%); filter: blur(40px); border-radius: 50%; pointer-events: none; z-index: 0;">
                                </div> --}}

                                <!-- Left content -->
                                <div class="col-lg-4 col-12 mb-4 mb-lg-0 z-1 text-left offer-copy-panel">
                                    <div class="offer-copy-layout d-flex flex-column">
                                        <!-- Badges -->
                                        <div class="offer-badges-container d-flex flex-wrap align-items-center mb-3"
                                            style="gap: 8px;">
                                            <span
                                                class="fs-10 fw-800 text-uppercase tracking-wider px-3 text-white offer-kicker"
                                                style="padding: 10px !important;border-radius: 30px; letter-spacing: 1px; ">
                                                {{ translate($offer->name) }}
                                            </span>
                                            @if ($offer->badge_text)
                                                @php
                                                    $badge_txt = $offer->badge_text;
                                                    if (
                                                        is_numeric($badge_txt) ||
                                                        (str_ends_with($badge_txt, '%') &&
                                                            !str_contains(strtolower($badge_txt), 'off'))
                                                    ) {
                                                        $badge_txt .= ' OFF';
                                                    }
                                                @endphp
                                                <span
                                                    class="fs-10 fw-800 text-uppercase tracking-wider px-2.5 py-1 offer-badge"
                                                    style="padding:10px;border-radius: 6px;">
                                                    {{ $badge_txt }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Title, Desc & Timer -->
                                        <div class="offer-title-desc-timer">
                                            <h2 class="fs-28 fs-md-36 fw-800 leading-tight mb-2"
                                                style="font-family: 'Outfit', sans-serif; letter-spacing: -0.8px;">
                                                {{ translate($offer->name) }}
                                            </h2>
                                            @if ($offer->custom_text)
                                                <p class="fs-14 fs-md-15 mb-4"
                                                    style="max-width: 420px; line-height: 1.6; font-weight: 450;">
                                                    {{ $offer->custom_text }}
                                                </p>
                                            @endif

                                            <!-- Premium countdown timer -->
                                            @if ($offer->ends_at)
                                                <div class="premium-countdown-timer d-flex align-items-center mb-4"
                                                    data-end-date="{{ $offer->ends_at->format('Y/m/d H:i:s') }}"
                                                    style="gap: 8px;">
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 54px; height: 58px; border-radius: 12px !important; ">
                                                        <span class="days fs-18 fw-800 text-dark"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-muted text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important;">Days</span>
                                                    </div>
                                                    <div class="fs-16 fw-700"
                                                        style="color: var(--primary) !important; line-height: 58px;">:
                                                    </div>
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 54px; height: 58px; border-radius: 12px !important; ">
                                                        <span class="hours fs-18 fw-800 text-dark"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-muted text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important;">Hours</span>
                                                    </div>
                                                    <div class="fs-16 fw-700"
                                                        style="color: var(--primary) !important; line-height: 58px;">:
                                                    </div>
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 54px; height: 58px; border-radius: 12px !important; ">
                                                        <span class="minutes fs-18 fw-800 text-dark"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-muted text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important;">Mins</span>
                                                    </div>
                                                    <div class="fs-16 fw-700"
                                                        style="color: var(--primary) !important; line-height: 58px;">:
                                                    </div>
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 54px; height: 58px; border-radius: 12px !important; ">
                                                        <span class="seconds fs-18 fw-800"
                                                            style="font-family: 'Outfit', sans-serif; line-height: 1.2;">00</span>
                                                        <span class="fs-9 fw-600 text-muted text-uppercase"
                                                            style="letter-spacing: 0.5px; font-size: 8px !important;">Secs</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($offer->products->count() > 0)
                                            <div class="offer-copy-btn">
                                                <a href="{{ route('product', $offer->products->first()->slug) }}"
                                                    class="btn px-4 py-2.5 text-white fw-700 fs-13 d-inline-flex align-items-center"
                                                    style="border-radius: 30px; transition: all 0.3s ease; gap: 8px;">
                                                    <span>{{ translate('Shop Now') }}</span>
                                                    <i class="las la-arrow-right"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right products grid using premium carousel and unified template -->
                                <div class="col-lg-8 col-12 z-1 offer-products-panel">
                                    <div class="aiz-carousel sm-gutters-16 offer-inner-carousel arrow-none{{ $offerProductCarouselClass }}"
                                        data-items="3" data-xl-items="3" data-lg-items="3" data-md-items="3"
                                        data-sm-items="2" data-xs-items="2" data-arrows="false"
                                        data-dots="{{ $offerProductCount > 3 ? 'true' : 'false' }}"
                                        data-infinite="false" data-autoplay="false">
                                        @foreach ($offer->products as $product)
                                            <div class="carousel-box p-1">
                                                @include(
                                                    'frontend.' .
                                                        get_setting('homepage_select') .
                                                        '.partials.product_box_1',
                                                    ['product' => $product]
                                                )
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endif
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
    <script type="text/javascript">
        function initOfferCarousel() {
            if (typeof $ !== 'undefined' && $.fn.slick) {
                $('.offer-carousel').each(function() {
                    var $this = $(this);
                    if ($this.hasClass('slick-initialized')) {
                        $this.slick('unslick');
                    }
                    $this.slick({
                        slidesToShow: 1,
                        autoplay: false,
                        dots: false,
                        arrows: true,
                        infinite: true,
                        prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>',
                        responsive: [{
                                breakpoint: 992,
                                settings: {
                                    arrows: true,
                                    dots: false
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    arrows: true,
                                    dots: false
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    arrows: true,
                                    dots: false
                                }
                            }
                        ]
                    });
                });

                // Prevent nested carousel touch events from bubbling up and triggering outer slider drag/swipes,
                // which blocks dot clicking and inner carousel swiping on mobile. We delegate on the static container
                // .offer-products-panel to intercept the events before they bubble up to the outer carousel.
                $('.offer-products-panel').on(
                    'mousedown touchstart touchmove touchend mouseup pointerdown pointermove pointerup',
                    '.offer-inner-carousel',
                    function(e) {
                        e.stopPropagation();
                    });
            } else {
                setTimeout(initOfferCarousel, 50);
            }
        }
        initOfferCarousel();
    </script>
@endif
