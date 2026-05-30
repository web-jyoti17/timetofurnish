<div class="mb-5 product-desc-tabs redesigned-product-tabs enhanced-tabs">
    <!-- Tabs Navigation -->
    <ul class="nav redesigned-tab-nav enhanced-tab-nav" role="tablist">
        <li>
            <a href="#tab_default_1" data-toggle="tab" class="redesigned-tab-link enhanced-tab-link active" role="tab">
                {{ translate('Description') }}
            </a>
        </li>
        @if (!empty($detailedProduct->specification))
            <li>
                <a href="#tab_default_4" data-toggle="tab" class="redesigned-tab-link enhanced-tab-link" role="tab">
                    {{ translate('Specifications') }}
                </a>
            </li>
        @endif
        @if (!empty($detailedProduct->video_link))
            <li>
                <a href="#tab_default_2" data-toggle="tab" class="redesigned-tab-link enhanced-tab-link" role="tab">
                    {{ translate('Video') }}
                </a>
            </li>
        @endif
        @if (!empty($detailedProduct->pdf))
            <li>
                <a href="#tab_default_3" data-toggle="tab" class="redesigned-tab-link enhanced-tab-link" role="tab">
                    {{ translate('Downloads') }}
                </a>
            </li>
        @endif
        <li>
            <a href="#tab_default_5" data-toggle="tab" class="redesigned-tab-link enhanced-tab-link" role="tab">
                {{ translate('Reviews & Ratings') }}
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content redesigned-tab-content enhanced-tab-content">

        <!-- Description -->
        <div class="tab-pane fade show active" id="tab_default_1" role="tabpanel">
            <div class="enhanced-editor-wrap">

                <div class="readmore-wrapper">
                    <div class="overflow-hidden text-left mw-100 aiz-editor-data description readmore-content">
                        {!! $detailedProduct->getTranslation('description') !!}
                    </div>
                    <div class="readmore-btn-wrap">
                        <span class="readmore-ellipsis">...</span>
                        <a href="javascript:void(0)" class="readmore-btn readmore-btn-link" role="button">
                            {{ translate('Read More') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Specification -->
        @if (!empty($detailedProduct->specification))
            <div class="tab-pane fade" id="tab_default_4" role="tabpanel">
                <div class="enhanced-editor-wrap">
                    <div class="readmore-wrapper">
                        <div class="overflow-hidden text-left mw-100 aiz-editor-data specification readmore-content">
                            {!! $detailedProduct->specification !!}
                        </div>
                        <div class="readmore-btn-wrap">
                            <span class="readmore-ellipsis">...</span>
                            <a href="javascript:void(0)" class="readmore-btn readmore-btn-link" role="button">
                                {{ translate('Read More') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Video -->
        @if (!empty($detailedProduct->video_link))
            <div class="tab-pane fade" id="tab_default_2" role="tabpanel">
                <div class="d-flex justify-content-center align-items-center"
                    style="min-height:250px; background:#f5f8fa;">
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
                                width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
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
                <div class="py-4 text-center">
                    <a href="{{ uploaded_asset($detailedProduct->pdf) }}"
                        class="btn btn-outline-primary btn-lg enhanced-download-btn">
                        <i class="la la-download fa-lg mr-1"></i> {{ translate('Download') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Review Tab Pane -->
        <div class="tab-pane fade" id="tab_default_5" role="tabpanel">
            <div class="enhanced-editor-wrap review-pane-wrap">
                @include('frontend.product_details.review_section')
            </div>
        </div>

    </div>
</div>

<style>
    /* SIMPLE CLEAN TAB DESIGN */
    .enhanced-tabs {
        background: transparent;
        border: none;
        border-radius: 0;
        padding: 0;
        box-shadow: none;
        overflow: visible;
        margin-top: 15px;
    }

    .enhanced-tab-nav {
        display: flex;
        align-items: stretch;
        flex-wrap: nowrap;
        background: transparent;
        border-bottom: 2px solid #e8e2db;
        border-radius: 0;
        margin-bottom: 0;
        padding: 0;
        gap: 0;
        list-style: none;
        justify-content: center;
    }

    .enhanced-tab-nav li {
        margin: 0;
        border-radius: 0;
        flex-shrink: 0;
    }

    .enhanced-tab-link {
        transition: color 0.18s, border-color 0.18s;
        font-size: 0.95rem;
        font-weight: 600;
        color: #8a7e72;
        padding: 12px 22px;
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        text-align: center;
        position: relative;
        min-width: unset;
        display: block;
        white-space: nowrap;
        margin-bottom: -2px;
        letter-spacing: 0.01em;
        text-decoration: none;
    }

    .enhanced-tab-link.active,
    .enhanced-tab-link:focus {
        color: #685b4e;
        background: transparent;
        border-bottom: 4px solid #685b4e;
        box-shadow: none;
        z-index: 2;
    }

    .enhanced-tab-link:hover:not(.active) {
        color: #685b4e;
        border-bottom-color: #c5b9ac;
    }

    .enhanced-tab-content {
        padding: 20px 0 0 0;
        background: transparent;
        min-height: 120px;
        border-radius: 0;
        position: relative;
    }

    .enhanced-editor-wrap {
        background: transparent;
        border: none;
        padding: 10px 0;
        border-radius: 0;
        min-height: 100px;
        box-shadow: none;
    }

    .review-pane-wrap {
        background: transparent;
        border: none;
        min-height: 0;
        box-shadow: none;
        padding: 0 !important;
    }

    /* --- Mobile: single row with horizontal scroll --- */
    @media (max-width: 768px) {
        .enhanced-tab-nav {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
            padding: 0;
            gap: 0;
        }

        .enhanced-tab-nav::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        .enhanced-tab-nav li {
            flex-shrink: 0;
        }

        .enhanced-tab-link {
            padding: 10px 16px;
            font-size: 0.88rem;
            white-space: nowrap;
        }

        .enhanced-tab-content {
            padding: 15px 0 0 0;
        }

        .enhanced-editor-wrap {
            padding: 8px 0;
        }
    }

    @media (max-width: 480px) {
        .enhanced-tab-link {
            padding: 9px 13px;
            font-size: 0.84rem;
        }

        .enhanced-tab-content {
            padding: 10px 0 0 0;
        }

        .enhanced-editor-wrap {
            padding: 5px 0;
        }
    }

    /* Description & Specification styles */
    .description,
    .specification {
        font-size: 1.09rem;
        color: #16253c;
        line-height: 1.92;
        letter-spacing: 0.01em;
        margin: 0;
        font-weight: 400;
    }

    .readmore-wrapper {
        position: relative;
    }

    .readmore-content {
        overflow: hidden;
        background: transparent;
        transition: max-height 0.36s cubic-bezier(.4, 0, .2, 1);
    }

    .readmore-btn-wrap-inline {
        display: inline !important;
        white-space: nowrap;
    }

    .readmore-ellipsis {
        color: #16253c;
        font-weight: 500;
        margin-left: 2px;
        margin-right: 2px;
    }

    .readmore-btn-link {
        color: #685b4e !important;
        font-weight: 700;
        text-decoration: underline !important;
        cursor: pointer;
        transition: color 0.15s ease;
        display: inline !important;
    }

    .readmore-btn-link:hover {
        color: #4a3e34 !important;
        text-decoration: underline !important;
    }

    /* Download button */
    .enhanced-download-btn {
        padding: 12px 48px;
        border-radius: 32px;
        font-size: 1.13rem;
        font-weight: 600;
        border: 2px solid #685b4e;
        color: #fff !important;
        background: linear-gradient(94deg, #685b4e, #8a7e72 96%);
        box-shadow: 0 4px 18px 0 rgba(104, 91, 78, 0.10);
        transition: background 0.15s, border 0.14s;
    }

    .enhanced-download-btn:hover {
        background: linear-gradient(94deg, #4a3e34, #685b4e 96%);
        border-color: #4a3e34;
        color: #fff !important;
    }

    /* Remove default nav styles */
    .nav.nav-pills,
    .nav-pills {
        background: transparent !important;
        border-bottom: none !important;
        box-shadow: none !important;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Recursive function to append toggle element into the very last child of a container
        function appendInlineToggle(container, element) {
            let lastChild = container.lastElementChild;
            while (lastChild && lastChild.lastElementChild && !['SPAN', 'A'].includes(lastChild.tagName)) {
                lastChild = lastChild.lastElementChild;
            }
            if (lastChild) {
                lastChild.appendChild(element);
            } else {
                container.appendChild(element);
            }
        }

        document.querySelectorAll('.readmore-wrapper').forEach(function(wrapper) {
            const content = wrapper.querySelector('.readmore-content');
            if (!content) return;

            // Save the original rich HTML content
            const originalHTML = content.innerHTML;
            content.setAttribute('data-original-html', originalHTML);

            // Clean plaintext to determine overflow
            const plainText = content.textContent.trim();
            
            // Hide the old absolute button wrapper completely
            const oldBtnWrap = wrapper.querySelector('.readmore-btn-wrap');
            if (oldBtnWrap) oldBtnWrap.style.display = 'none';

            if (plainText.length > 280) {
                const truncatedText = plainText.substring(0, 280).trim();
                
                // Create collapsed inline wrapper
                const toggleSpan = document.createElement('span');
                toggleSpan.className = 'readmore-btn-wrap-inline';
                toggleSpan.innerHTML = '<span class="readmore-ellipsis">...</span><a href="javascript:void(0)" class="readmore-btn readmore-btn-link">' + "{{ translate('Read More') }}" + '</a>';
                
                content.innerHTML = truncatedText;
                content.appendChild(toggleSpan);
                wrapper.classList.remove('expanded');
            }
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
            // Re-initialize isn't strictly necessary since elements are modified, but handles resizing safely
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('readmore-btn')) {
                e.preventDefault();
                const button = e.target;
                const wrapper = button.closest('.readmore-wrapper');
                const content = wrapper.querySelector('.readmore-content');
                if (!content) return;
                
                const originalHTML = content.getAttribute('data-original-html');
                const isExpanded = wrapper.classList.contains('expanded');

                if (isExpanded) {
                    // Collapse back to text
                    const plainText = content.textContent.trim().replace("Read Less", "").trim();
                    const truncatedText = plainText.substring(0, 280).trim();
                    
                    const toggleSpan = document.createElement('span');
                    toggleSpan.className = 'readmore-btn-wrap-inline';
                    toggleSpan.innerHTML = '<span class="readmore-ellipsis">...</span><a href="javascript:void(0)" class="readmore-btn readmore-btn-link">' + "{{ translate('Read More') }}" + '</a>';
                    
                    content.innerHTML = truncatedText;
                    content.appendChild(toggleSpan);
                    wrapper.classList.remove('expanded');
                    
                    wrapper.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                } else {
                    // Expand and append Read Less directly inside the last text node
                    content.innerHTML = originalHTML;
                    
                    const toggleSpan = document.createElement('span');
                    toggleSpan.className = 'readmore-btn-wrap-inline';
                    toggleSpan.innerHTML = ' <a href="javascript:void(0)" class="readmore-btn readmore-btn-link">' + "{{ translate('Read Less') }}" + '</a>';
                    
                    appendInlineToggle(content, toggleSpan);
                    wrapper.classList.add('expanded');
                }
            }
        });
    });
</script>
