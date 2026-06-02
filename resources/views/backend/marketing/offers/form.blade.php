@php
    $is_admin = Auth::user()->user_type === 'admin' || Auth::user()->user_type === 'staff';
    $busy_product_ids = \App\Models\Offer::getBusyProductIds(isset($offer) ? $offer->id : null);
@endphp

<style>
    /* Premium visual styling representing Time to Furnish design system */
    .theme-card {
        background-color: #faf8f5 !important;
        border: 1px solid #e5dec9 !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 30px rgba(104, 91, 78, 0.04) !important;
        transition: all 0.3s ease;
    }
    .theme-card:hover {
        box-shadow: 0 12px 40px rgba(104, 91, 78, 0.08) !important;
    }
    .theme-card-header {
        border-bottom: 1px solid #e5dec9 !important;
        background-color: transparent !important;
        padding: 1.5rem 1.5rem 1rem 1.5rem !important;
    }
    .theme-card-title {
        color: #39322a !important;
        font-weight: 700 !important;
        font-size: 1.05rem !important;
        letter-spacing: -0.2px;
        display: flex;
        align-items: center;
    }
    .theme-card-title i {
        color: #685b4e !important;
        font-size: 1.3rem;
    }
    .theme-card-body {
        padding: 1.5rem !important;
    }
    .theme-form-label {
        color: #554a3f !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.8px !important;
        margin-bottom: 0.5rem !important;
    }
    .form-control, .bootstrap-select > .dropdown-toggle {
        background-color: #ffffff !important;
        border: 1px solid #dcd5c5 !important;
        border-radius: 10px !important;
        color: #39322a !important;
        padding: 10px 16px !important;
        height: auto !important;
        font-size: 0.95rem !important;
        transition: all 0.2s ease-in-out !important;
    }
    .form-control:focus, .bootstrap-select > .dropdown-toggle:focus {
        border-color: #685b4e !important;
        box-shadow: 0 0 0 3px rgba(104, 91, 78, 0.12) !important;
        outline: none !important;
    }
    .theme-btn-primary {
        background-color: #685b4e !important;
        border-color: #685b4e !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px;
        padding: 12px 35px !important;
        border-radius: 50px !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .theme-btn-primary:hover, .theme-btn-primary:focus {
        background-color: #554a3f !important;
        border-color: #554a3f !important;
        color: #ffffff !important;
        box-shadow: 0 8px 24px rgba(104, 91, 78, 0.2) !important;
        transform: translateY(-2px) !important;
    }
    .theme-btn-primary:active {
        transform: translateY(0) !important;
    }
    
    /* Dynamic checkbox and selectpicker modifications */
    .dropdown-menu {
        border-radius: 12px !important;
        border: 1px solid #e5dec9 !important;
        box-shadow: 0 10px 30px rgba(104, 91, 78, 0.1) !important;
        padding: 8px !important;
    }
    .dropdown-menu .dropdown-item {
        border-radius: 8px !important;
        padding: 8px 12px !important;
        color: #554a3f !important;
        transition: all 0.15s ease;
    }
    .dropdown-menu .dropdown-item:hover, .dropdown-menu .dropdown-item.active, .dropdown-menu .dropdown-item:active {
        background-color: #f3eee6 !important;
        color: #39322a !important;
    }
    
    /* Overriding AIZ Switch and standard success switches */
    .aiz-switch-success input:checked + span {
        background-color: #685b4e !important;
    }
    .aiz-switch input:checked + span {
        background-color: #685b4e !important;
    }
    
    .text-muted-theme {
        color: #8a7d6e !important;
    }
    .border-theme-top {
        border-top: 1px solid #e5dec9 !important;
    }
</style>

<input type="hidden" name="timezone_offset" class="timezone-offset-field" value="0">

<div class="row">
    <!-- Main Info Column -->
    <div class="col-lg-7">
        <div class="card theme-card mb-4">
            <div class="card-header theme-card-header">
                <h6 class="card-title mb-0 theme-card-title"><i class="las la-tags mr-2"></i>{{ translate('Offer Details') }}</h6>
            </div>
            <div class="card-body theme-card-body">
                <!-- Offer Name selection (Dropdown + Custom) -->
                <div class="form-group mb-4">
                    <label class="form-label theme-form-label" for="offer_name_select">{{ translate('Offer / Deal Name') }} <span class="text-danger">*</span></label>
                    <select id="offer_name_select" class="form-control aiz-selectpicker rounded-lg border-gray-300" required>
                        @php
                            $current_name = isset($offer) ? $offer->name : '';
                            $options = get_offer_name_options();
                            $is_custom = $current_name && !isset($options[$current_name]);
                        @endphp
                        @foreach($options as $key => $val)
                            <option value="{{ $key }}" @if($current_name == $key) selected @endif>{{ translate($val) }}</option>
                        @endforeach
                        <option value="custom" @if($is_custom) selected @endif>{{ translate('-- Enter Custom Offer Name --') }}</option>
                    </select>
                </div>

                <!-- Custom Offer Name (hidden by default) -->
                <div class="form-group mb-4" id="custom_name_wrapper" style="display: @if($is_custom) block @else none @endif;">
                    <label class="form-label theme-form-label" for="name">{{ translate('Custom Offer Name') }} <span class="text-danger">*</span></label>
                    <input type="text" placeholder="{{ translate('Enter your custom offer name') }}" id="name" name="name" class="form-control rounded-lg" value="{{ isset($offer) ? $offer->name : array_key_first(get_offer_name_options()) }}" required>
                </div>

                <!-- Custom Text / Description -->
                <div class="form-group mb-0">
                    <label class="form-label theme-form-label" for="custom_text">{{ translate('Offer Description Text') }}</label>
                    <textarea placeholder="{{ translate('Write some amazing details about this offer...') }}" id="custom_text" name="custom_text" class="form-control rounded-lg" rows="4">{{ isset($offer) ? $offer->custom_text : '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="card theme-card mb-4">
            <div class="card-header theme-card-header">
                <h6 class="card-title mb-0 theme-card-title"><i class="las la-gifts mr-2"></i>{{ translate('Products under Offer') }} <span class="text-danger">*</span></h6>
            </div>
            <div class="card-body theme-card-body">
                <!-- Products list -->
                <div class="form-group mb-0">
                    <label class="form-label theme-form-label">{{ translate('Choose Products') }}</label>
                    <select name="products[]" id="products" class="form-control aiz-selectpicker rounded-lg border-gray-300" multiple required data-placeholder="{{ translate('Select Products') }}" data-live-search="true" data-selected-text-format="count" data-actions-box="true">
                        @foreach($products as $product)
                            @php
                                $is_selected = isset($offer) && $offer->products->contains($product->id);
                            @endphp
                            <option value="{{ $product->id }}" @if($is_selected) selected @endif>
                                {{ $product->getTranslation('name') }}
                            </option>
                        @endforeach
                    </select>
                    <span class="fs-11 text-muted-theme mt-2 d-block"><i class="las la-info-circle mr-1"></i>{{ translate('Admins and sellers can associate products with multiple active offers simultaneously.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing & Validity Column -->
    <div class="col-lg-5">
        <div class="card theme-card mb-4">
            <div class="card-header theme-card-header">
                <h6 class="card-title mb-0 theme-card-title"><i class="las la-calculator mr-2"></i>{{ translate('Pricing & Discount') }}</h6>
            </div>
            <div class="card-body theme-card-body">
                <!-- Discount Type -->
                <div class="form-group mb-4">
                    <label class="form-label theme-form-label" for="discount_type">{{ translate('Discount Type') }} <span class="text-danger">*</span></label>
                    <select name="discount_type" id="discount_type" class="form-control aiz-selectpicker rounded-lg border-gray-300" required>
                        <option value="percentage" @if(isset($offer) && $offer->discount_type == 'percentage') selected @endif>{{ translate('Percentage Discount (e.g. 50% off)') }}</option>
                        <option value="fixed" @if(isset($offer) && $offer->discount_type == 'fixed') selected @endif>{{ translate('Fixed Amount Discount (e.g. £100 off)') }}</option>
                        <option value="badge_only" @if(isset($offer) && $offer->discount_type == 'badge_only') selected @endif>{{ translate('Badge Only (No display pricing changes)') }}</option>
                    </select>
                </div>

                <!-- Discount Value -->
                @php
                    $is_badge_only = isset($offer) && $offer->discount_type == 'badge_only';
                @endphp
                <div class="form-group mb-4" id="discount_value_wrapper" style="display: @if($is_badge_only) none @else block @endif;">
                    <label class="form-label theme-form-label" for="discount_value">{{ translate('Discount Value') }} <span class="text-danger">*</span></label>
                    <input type="number" min="0" step="0.01" placeholder="{{ translate('50') }}" id="discount_value" name="discount_value" class="form-control rounded-lg" value="{{ isset($offer) ? $offer->discount_value : '' }}" @if(!$is_badge_only) required @endif>
                </div>

                <!-- Badge Text -->
                <div class="form-group mb-0">
                    <label class="form-label theme-form-label" for="badge_text">{{ translate('Badge Text') }} <small>({{ translate('e.g. 50% OFF, HOT') }})</small></label>
                    <input type="text" placeholder="{{ translate('50% OFF') }}" id="badge_text" name="badge_text" class="form-control rounded-lg" value="{{ isset($offer) ? $offer->badge_text : '' }}">
                </div>
            </div>
        </div>

        <div class="card theme-card mb-4">
            <div class="card-header theme-card-header">
                <h6 class="card-title mb-0 theme-card-title"><i class="las la-calendar-check mr-2"></i>{{ translate('Validity & Settings') }}</h6>
            </div>
            <div class="card-body theme-card-body">
                <!-- Validity Date Range -->
                <div class="form-group mb-4">
                    <label class="form-label theme-form-label" for="starts_at">{{ translate('Start Date') }}</label>
                    <input type="datetime-local" id="starts_at" name="starts_at" class="form-control rounded-lg" value="{{ isset($offer) && $offer->starts_at ? $offer->starts_at->format('Y-m-d\TH:i') : '' }}">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label theme-form-label" for="ends_at">{{ translate('End Date') }}</label>
                    <input type="datetime-local" id="ends_at" name="ends_at" class="form-control rounded-lg" value="{{ isset($offer) && $offer->ends_at ? $offer->ends_at->format('Y-m-d\TH:i') : '' }}">
                </div>

                <!-- Priority -->
                <div class="form-group mb-4">
                    <label class="form-label theme-form-label" for="priority">{{ translate('Priority') }}</label>
                    <input type="number" min="0" placeholder="{{ translate('0') }}" id="priority" name="priority" class="form-control rounded-lg" value="{{ isset($offer) ? $offer->priority : 0 }}">
                    <span class="fs-11 text-muted-theme mt-1 d-block">{{ translate('Higher priority wins if a product has multiple active offers.') }}</span>
                </div>

                <!-- Homepage Template Style Selection -->
                <div class="form-group mb-4 pt-3 border-theme-top" id="template_style_wrapper">
                    <label class="form-label theme-form-label" for="template_style">{{ translate('Homepage Template Style') }}</label>
                    <select name="template_style" id="template_style" class="form-control aiz-selectpicker rounded-lg border-gray-300" required>
                        <option value="style_1" @if(isset($offer) && $offer->template_style == 'style_1') selected @endif>{{ translate('Style 1: Premium Glassmorphism') }}</option>
                        <option value="style_2" @if(isset($offer) && $offer->template_style == 'style_2') selected @endif>{{ translate('Style 2: Dark Warm Split') }}</option>
                        <option value="style_3" @if(isset($offer) && $offer->template_style == 'style_3') selected @endif>{{ translate('Style 3: Minimalist Outline Frame') }}</option>
                    </select>
                    <span class="fs-11 text-muted-theme mt-1 d-block">{{ translate('Select the display layout style for the homepage.') }}</span>
                </div>

                <!-- Template Preview Container -->
                <div class="form-group mb-4" id="template_preview_wrapper">
                    <label class="form-label theme-form-label">{{ translate('Template Style Live Preview') }}</label>
                    <div id="template_preview_container" class="p-0 border rounded-lg bg-white position-relative" style="min-height: 150px; border-color: #e5dec9 !important; transition: all 0.3s ease;">
                        <!-- Scaled visual preview mockup -->
                    </div>
                </div>

                <!-- Show on Homepage (Admin Only) -->
                @if($is_admin)
                    <div class="form-group mb-0 d-flex justify-content-between align-items-center pt-3 border-theme-top">
                        <div>
                            <label class="form-label fw-700 fs-13 mb-0 text-dark" for="show_on_home">{{ translate('Display on Homepage') }}</label>
                            <span class="fs-11 text-muted-theme d-block">{{ translate('Show this deal in the homepage slider.') }}</span>
                        </div>
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" name="show_on_home" value="1" @if(isset($offer) && $offer->show_on_home == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12 text-right">
        <button type="submit" class="btn theme-btn-primary px-5 shadow-sm">
            <i class="las la-check-circle mr-2 fs-16"></i>
            @if(isset($offer))
                {{ translate('Update Offer') }}
            @else
                {{ translate('Submit Offer') }}
            @endif
        </button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.timezone-offset-field').val(new Date().getTimezoneOffset());

        // Toggle Custom Name input depending on selection
        $('#offer_name_select').on('change', function() {
            var selected = $(this).val();
            if (selected === 'custom') {
                $('#custom_name_wrapper').show();
                $('#name').val('');
                $('#name').attr('required', 'required');
            } else {
                $('#custom_name_wrapper').hide();
                $('#name').val(selected);
                $('#name').removeAttr('required');
            }
        });

        // Hide discount value if Badge Only is selected
        $('#discount_type').on('change', function() {
            var type = $(this).val();
            if (type === 'badge_only') {
                $('#discount_value_wrapper').hide();
                $('#discount_value').removeAttr('required');
            } else {
                $('#discount_value_wrapper').show();
                $('#discount_value').attr('required', 'required');
            }
        });

        // Update live preview depending on style selection
        function updateTemplatePreview() {
            var style = $('#template_style').val();
            var container = $('#template_preview_container');
            var html = '';
            
            if (style === 'style_1') {
                html = `
                    <div class="p-3 rounded" style="background: linear-gradient(135deg, #fdfbf7 0%, #f7f0e3 50%, #eadfc9 100%); border: 1px solid #e2d7c0; min-height: 150px;">
                        <div class="row align-items-center" style="margin:0;">
                            <div class="col-7 pl-0 pr-1">
                                <div style="width: 55px; height: 12px; background: #685b4e; border-radius: 3px; margin-bottom: 6px;"></div>
                                <div style="width: 90%; height: 16px; background: #3e3327; border-radius: 3px; margin-bottom: 6px;"></div>
                                <div style="width: 70%; height: 8px; background: #8c8276; border-radius: 2px; margin-bottom: 12px;"></div>
                                <div class="d-flex mb-2" style="gap: 4px;">
                                    <div style="width: 16px; height: 16px; background: #fff; border: 1px solid rgba(226,156,9,0.25); border-radius: 4px;"></div>
                                    <div style="width: 16px; height: 16px; background: #fff; border: 1px solid rgba(226,156,9,0.25); border-radius: 4px;"></div>
                                    <div style="width: 16px; height: 16px; background: #fff; border: 1px solid rgba(226,156,9,0.25); border-radius: 4px;"></div>
                                </div>
                                <div style="width: 60px; height: 18px; background: #685b4e; border-radius: 9px;"></div>
                            </div>
                            <div class="col-5 pl-0 pr-0 d-flex" style="gap: 4px;">
                                <div class="p-1 rounded bg-white text-center" style="border: 1px solid rgba(226,215,192,0.5); width: 50%;">
                                    <div style="height: 45px; background: #f5f5f5; border-radius: 4px; margin-bottom: 4px;"></div>
                                    <div style="width: 80%; height: 5px; background: #ddd; border-radius: 2px; margin: 0 auto 3px;"></div>
                                    <div style="width: 50%; height: 5px; background: #685b4e; border-radius: 2px; margin: 0 auto;"></div>
                                </div>
                                <div class="p-1 rounded bg-white text-center" style="border: 1px solid rgba(226,215,192,0.5); width: 50%;">
                                    <div style="height: 45px; background: #f5f5f5; border-radius: 4px; margin-bottom: 4px;"></div>
                                    <div style="width: 80%; height: 5px; background: #ddd; border-radius: 2px; margin: 0 auto 3px;"></div>
                                    <div style="width: 50%; height: 5px; background: #685b4e; border-radius: 2px; margin: 0 auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if (style === 'style_2') {
                html = `
                    <div class="rounded overflow-hidden d-flex" style="background: #fbfaf7; border: 1px solid #e2d7c0; min-height: 150px;">
                        <div class="p-3 d-flex flex-column justify-content-center text-white" style="background: linear-gradient(135deg, #51463a 0%, #685b4e 100%); width: 45%;">
                            <div style="width: 35px; height: 10px; background: rgba(255,255,255,0.4); border-radius: 3px; margin-bottom: 6px;"></div>
                            <div style="width: 90%; height: 16px; background: #ffffff; border-radius: 3px; margin-bottom: 6px;"></div>
                            <div style="width: 70%; height: 8px; background: rgba(255,255,255,0.6); border-radius: 2px; margin-bottom: 10px;"></div>
                            <div class="d-flex mb-2" style="gap: 4px;">
                                <div style="width: 14px; height: 14px; background: rgba(255,255,255,0.25); border-radius: 50%;"></div>
                                <div style="width: 14px; height: 14px; background: rgba(255,255,255,0.25); border-radius: 50%;"></div>
                                <div style="width: 14px; height: 14px; background: rgba(255,255,255,0.25); border-radius: 50%;"></div>
                            </div>
                            <div style="width: 55px; height: 16px; background: #ffffff; border-radius: 8px;"></div>
                        </div>
                        <div class="p-2 d-flex align-items-center justify-content-around" style="width: 55%; background: #fbfaf7; gap: 4px;">
                            <div class="p-1 rounded bg-white text-center shadow-sm" style="border: 1px solid rgba(226,215,192,0.3); width: 48%;">
                                <div style="height: 45px; background: #f5f5f5; border-radius: 4px; margin-bottom: 4px;"></div>
                                <div style="width: 80%; height: 5px; background: #ddd; border-radius: 2px; margin: 0 auto 3px;"></div>
                                <div style="width: 50%; height: 5px; background: #685b4e; border-radius: 2px; margin: 0 auto;"></div>
                            </div>
                            <div class="p-1 rounded bg-white text-center shadow-sm" style="border: 1px solid rgba(226,215,192,0.3); width: 48%;">
                                <div style="height: 45px; background: #f5f5f5; border-radius: 4px; margin-bottom: 4px;"></div>
                                <div style="width: 80%; height: 5px; background: #ddd; border-radius: 2px; margin: 0 auto 3px;"></div>
                                <div style="width: 50%; height: 5px; background: #685b4e; border-radius: 2px; margin: 0 auto;"></div>
                            </div>
                        </div>
                    </div>
                `;
            } else if (style === 'style_3') {
                html = `
                    <div class="p-3 rounded" style="background: #fafaf8; border: 3px double #685b4e; min-height: 150px;">
                        <div class="text-center mb-2" style="border-bottom: 1px solid rgba(104,91,78,0.2); padding-bottom: 6px;">
                            <div style="width: 60px; height: 10px; background: #685b4e; border-radius: 3px; margin: 0 auto 4px;"></div>
                            <div style="width: 130px; height: 14px; background: #3e3327; border-radius: 2px; margin: 0 auto 4px;"></div>
                            <div style="font-size: 9px; color: #685b4e; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase;">ENDS IN: 02D : 14H : 32M</div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                            <div class="p-1 rounded bg-white text-center" style="border: 1px solid #eee; width: 35%;">
                                <div style="height: 40px; background: #f8f8f8; border-radius: 2px; margin-bottom: 3px;"></div>
                                <div style="width: 80%; height: 4px; background: #eee; border-radius: 1px; margin: 0 auto 2px;"></div>
                                <div style="width: 40%; height: 4px; background: #3e3327; border-radius: 1px; margin: 0 auto;"></div>
                            </div>
                            <div class="p-1 rounded bg-white text-center" style="border: 1px solid #eee; width: 35%;">
                                <div style="height: 40px; background: #f8f8f8; border-radius: 2px; margin-bottom: 3px;"></div>
                                <div style="width: 80%; height: 4px; background: #eee; border-radius: 1px; margin: 0 auto 2px;"></div>
                                <div style="width: 40%; height: 4px; background: #3e3327; border-radius: 1px; margin: 0 auto;"></div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            container.html(html);
        }

        $('#template_style').on('change', updateTemplatePreview);
        updateTemplatePreview();

        // Toggle visibility of template selector based on show_on_home for admin
        function toggleTemplateStyleVisibility() {
            var showOnHomeCheckbox = $('input[name="show_on_home"]');
            if (showOnHomeCheckbox.length > 0) {
                if (showOnHomeCheckbox.is(':checked')) {
                    $('#template_style_wrapper').slideDown();
                    $('#template_preview_wrapper').slideDown();
                } else {
                    $('#template_style_wrapper').slideUp();
                    $('#template_preview_wrapper').slideUp();
                }
            }
        }

        $('input[name="show_on_home"]').on('change', toggleTemplateStyleVisibility);
        toggleTemplateStyleVisibility();
    });
</script>
