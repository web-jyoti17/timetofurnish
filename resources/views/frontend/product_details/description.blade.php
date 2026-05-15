<div class="mb-5 product-desc-tabs custom-tabs">
    <!-- Tabs Navigation -->
    <div class="nav custom-nav-tabs" role="tablist">
        <a href="#tab_default_1" data-toggle="tab"
           class="custom-tab-link active"
           role="tab">
            <i class="mr-2 las la-file-alt"></i>
            {{ translate('Description') }}
        </a>

        @if (!empty($detailedProduct->specification))
            <a href="#tab_default_4" data-toggle="tab"
               class="custom-tab-link"
               role="tab">
                <i class="mr-2 las la-list-alt"></i>
                {{ translate('Specifications') }}
            </a>
        @endif

        @if (!empty($detailedProduct->video_link))
            <a href="#tab_default_2" data-toggle="tab"
               class="custom-tab-link"
               role="tab">
                <i class="mr-2 las la-video"></i>
                {{ translate('Video') }}
            </a>
        @endif

        @if (!empty($detailedProduct->pdf))
            <a href="#tab_default_3" data-toggle="tab"
               class="custom-tab-link"
               role="tab">
                <i class="mr-2 las la-download"></i>
                {{ translate('Downloads') }}
            </a>
        @endif
    </div>

    <!-- Tab Content -->
    <div class="tab-content custom-tab-content">

        <!-- Description -->
        <div class="tab-pane fade show active" id="tab_default_1" role="tabpanel">
            <div class="custom-editor-wrap">

                <div class="readmore-wrapper">
                    <div class="overflow-hidden text-left mw-100 aiz-editor-data description readmore-content">
                        {!! $detailedProduct->getTranslation('description') !!}
                    </div>

                    <button class="readmore-btn" type="button">
                        {{ translate('Read More') }}
                    </button>
                </div>

            </div>
        </div>

        <!-- Specification -->
        @if (!empty($detailedProduct->specification))
            <div class="tab-pane fade" id="tab_default_4" role="tabpanel">
                <div class="custom-editor-wrap">

                    <div class="readmore-wrapper">
                        <div class="overflow-hidden text-left mw-100 aiz-editor-data specification readmore-content">
                            {!! $detailedProduct->specification !!}
                        </div>

                        <button class="readmore-btn" type="button">
                            {{ translate('Read More') }}
                        </button>
                    </div>

                </div>
            </div>
        @endif

        <!-- Video -->
        @if (!empty($detailedProduct->video_link))
            <div class="tab-pane fade" id="tab_default_2" role="tabpanel">
                <div class="d-flex justify-content-center align-items-center" style="min-height:300px; background:#F5F5F5;">
                    <div class="embed-responsive embed-responsive-16by9" style="max-width:640px;width:100%;">

                        @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                            <iframe class="border rounded embed-responsive-item"
                                    src="https://www.youtube.com/embed/{{ get_url_params($detailedProduct->video_link, 'v') }}">
                            </iframe>

                        @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                            <iframe class="border rounded embed-responsive-item"
                                    src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}">
                            </iframe>

                        @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                            <iframe class="border rounded"
                                    src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}"
                                    width="500"
                                    height="281"
                                    frameborder="0"
                                    webkitallowfullscreen
                                    mozallowfullscreen
                                    allowfullscreen>
                            </iframe>
                        @endif

                    </div>
                </div>
            </div>
        @endif

        <!-- PDF -->
        @if (!empty($detailedProduct->pdf))
            <div class="tab-pane fade" id="tab_default_3" role="tabpanel">
                <div class="py-5 text-center">
                    <a href="{{ uploaded_asset($detailedProduct->pdf) }}"
                       class="px-5 shadow-sm btn btn-outline-primary btn-lg rounded-pill">
                        <i class="mr-2 las la-download"></i>
                        {{ translate('Download') }}
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    .product-desc-tabs.custom-tabs {
        background: #fbf9f7;
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 16px 0 rgba(180,172,164,0.08);
        padding: 8px 8px 0 8px;
        overflow: visible;
    }

    .custom-nav-tabs {
        display: flex;
        flex-wrap: nowrap;
        justify-content: center;
        gap: 0;
        background: #e0d6cb;
        border-radius: 16px 16px 0 0;
        padding: 0.5rem 1rem;
        border-bottom: none;
        position: relative;
        min-height: 58px;
    }

    .custom-tab-link {
        display: flex;
        align-items: center;
        font-weight: 700;
        font-size: 1.18rem;
        color: #252525;
        margin-right: 10px;
        min-width: 240px;
        height: 48px;
        padding: 0 28px;
        transition: all 0.15s cubic-bezier(.4,0,.2,1);
        position: relative;
        z-index: 1;
    }

    .custom-tab-link:last-child {
        margin-right: 0;
    }

    .custom-tab-link.active,
    .custom-tab-link:focus,
    .custom-tab-link:hover {
        color: #212121 !important;
        border-bottom: 1px solid #000 !important;
    }

    .custom-tab-link.active {
        font-weight: 800;
        letter-spacing: 0.05em;
        z-index: 3;
    }

    .custom-tab-content {
        background: #f7f6f3;
        padding: 0;
        min-height: 265px;
    }

    .custom-editor-wrap {
        background: #fff;
        border: 1.5px solid #eee6dd;
        padding: 25px 32px;
        box-shadow: 0 2px 16px 0 rgba(170,164,156,0.07);
        min-height: 180px;
    }

    .description,
    .specification {
        font-size: 1.12rem;
        color: #36322d;
        text-align: left;
        line-height: 2.1;
        letter-spacing: 0.15px;
        margin: 0;
    }

    /* READ MORE */

    .readmore-wrapper {
        position: relative;
    }

    .readmore-content {
        max-height: 140px;
        overflow: hidden;
        position: relative;
        transition: all 0.35s ease;
    }

    .readmore-content.expanded {
        max-height: 10000px;
    }

    .readmore-content:not(.expanded)::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 70px;
        background: linear-gradient(to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,1));
    }

    .readmore-btn {
        border: none;
        background: transparent;
        color: #000;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        padding: 0;
        margin-top: 12px;
        text-decoration: underline;
    }

    .readmore-btn:hover {
        opacity: 0.8;
    }

    /* Responsive */

    @media (max-width: 991px) {
        .custom-nav-tabs {
            flex-direction: row;
            gap: 10px;
            padding: 0.5rem 0.25rem 0rem 0.25rem;
        }

        .custom-tab-link {
            margin-bottom: 5px;
            margin-right: 0;
            min-width: unset;
            width: 100%;
            text-align: left;
            padding: 0 12px;
        }

        .product-desc-tabs.custom-tabs {
            border-radius: 12px;
        }
    }

    @media (max-width: 575px) {
        .custom-tab-link {
            font-size: 1rem;
            height: 38px;
        }

        .custom-editor-wrap {
            padding: 8px;
        }
    }

    .nav.nav-pills,
    .nav-pills {
        box-shadow: none !important;
        background: transparent !important;
        border-bottom: none !important;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        function initReadMore(wrapper) {

            const content = wrapper.querySelector('.readmore-content');
            const button = wrapper.querySelector('.readmore-btn');

            if (!content || !button) return;

            // Reset first
            button.style.display = 'none';

            // Need timeout because hidden tabs need rendering first
            setTimeout(() => {

                if (content.scrollHeight > 140) {
                    button.style.display = 'inline-block';
                }

            }, 100);
        }

        // Init all visible tabs
        document.querySelectorAll('.readmore-wrapper').forEach(function(wrapper) {
            initReadMore(wrapper);
        });

        // Bootstrap tab shown event
        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {

            document.querySelectorAll('.readmore-wrapper').forEach(function(wrapper) {
                initReadMore(wrapper);
            });

        });

        // Toggle functionality
        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('readmore-btn')) {

                const button = e.target;
                const wrapper = button.closest('.readmore-wrapper');
                const content = wrapper.querySelector('.readmore-content');

                content.classList.toggle('expanded');

                if (content.classList.contains('expanded')) {

                    button.innerText = "{{ translate('Read Less') }}";

                } else {

                    button.innerText = "{{ translate('Read More') }}";

                    wrapper.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });

    });
</script>
