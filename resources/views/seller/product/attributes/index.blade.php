@extends('seller.layouts.app')

@section('panel_content')
    @php
        $getDescendants = function ($category) use (&$getDescendants) {
            $ids = [];
            foreach ($category->childrenCategories as $child) {
                $ids[] = (string) $child->id;
                $ids = array_merge($ids, $getDescendants($child));
            }
            return $ids;
        };

        $renderCategoryOptions = function ($categories, $selected = [], $depth = 0) use (&$renderCategoryOptions, $getDescendants) {
            foreach ($categories as $category) {
                $childrenIds = $getDescendants($category);
                $prefix = '';
                if ($depth > 0) {
                    $prefix = str_repeat('&nbsp;&nbsp;&nbsp;', $depth) . '↳&nbsp;';
                }
                echo '<option value="' . $category->id . '" data-children-ids="' . implode(',', $childrenIds) . '"' . (in_array($category->id, $selected) ? ' selected' : '') . '>' . $prefix . e($category->getTranslation('name')) . '</option>';
                $renderCategoryOptions($category->childrenCategories, $selected, $depth + 1);
            }
        };
    @endphp

    <style>
        /* ── Design tokens ─────────────────────────────────────────────── */
        .admin-catalog-page {
            --catalog-theme: #b57a45;
            --catalog-theme-soft: rgba(181, 122, 69, 0.08);
            --catalog-theme-glow: rgba(181, 122, 69, 0.15);
            --catalog-danger: #e04f32;
            --catalog-danger-soft: rgba(224, 79, 50, 0.08);
            --catalog-success: #2e7d32;
            --catalog-bg-light: #faf8f5;
            --border-radius-sm: 8px;
            --border-radius-md: 12px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 1280px;
            margin: 0 auto;
            font-family: 'Public Sans', sans-serif;
        }

        /* ── Page header ───────────────────────────────────────────────── */
        .admin-catalog-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin: 20px 0 24px;
            padding: 24px;
            background: linear-gradient(135deg, var(--catalog-theme-soft) 0%, rgba(255, 255, 255, 0.8) 100%);
            border-radius: var(--border-radius-md);
            border: 1px solid rgba(181, 122, 69, 0.12);
        }

        .admin-catalog-eyebrow {
            color: var(--catalog-theme);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 6px;
        }

        .admin-catalog-title {
            margin: 2px 0 0;
            color: #2c2013;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .admin-catalog-subtitle {
            margin: 8px 0 0;
            color: #615a51;
            font-size: 14px;
            max-width: 800px;
            line-height: 1.5;
        }

        /* ── Cards ──────────────────────────────────────────────────── */
        .admin-catalog-card,
        .attr-editor-card {
            border: 1.5px solid #e8e4de;
            border-radius: var(--border-radius-md);
            background: #fff;
            box-shadow: 0 4px 20px rgba(44, 32, 19, 0.03);
            transition: var(--transition-smooth);
        }

        .admin-catalog-card {
            margin-bottom: 20px;
        }

        .admin-catalog-card-header,
        .attr-editor-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1.5px solid #e8e4de;
            background: #fff;
            border-top-left-radius: calc(var(--border-radius-md) - 1.5px);
            border-top-right-radius: calc(var(--border-radius-md) - 1.5px);
        }

        .admin-catalog-card-header h5,
        .attr-editor-head h5 {
            margin: 0;
            color: #2c2013;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -0.2px;
        }

        .admin-catalog-card-body {
            padding: 24px;
            background: var(--catalog-bg-light);
            border-bottom-left-radius: calc(var(--border-radius-md) - 1.5px);
            border-bottom-right-radius: calc(var(--border-radius-md) - 1.5px);
        }

        /* ── Chips ──────────────────────────────────────────────────── */
        .admin-catalog-chip {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            margin: 4px 6px 4px 0;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--catalog-theme-soft);
            border: 1px solid rgba(181, 122, 69, 0.15);
            color: var(--catalog-theme);
            font-size: 12px;
            font-weight: 700;
            transition: var(--transition-smooth);
        }

        .admin-catalog-chip:hover {
            background: var(--catalog-theme);
            color: #fff;
            transform: translateY(-1px);
        }

        .admin-catalog-chip-muted {
            color: #8e8376;
            background: #fbfbf9;
            border: 1.5px dashed #ded6cd;
        }

        /* ── Form ───────────────────────────────────────────────────── */
        .admin-catalog-form label {
            color: #4a3f35;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
        }

        .admin-catalog-form .form-control,
        .admin-catalog-form .bootstrap-select .dropdown-toggle {
            min-height: 44px;
            border: 1.5px solid #ded6cd !important;
            border-radius: var(--border-radius-sm) !important;
            box-shadow: none !important;
            transition: var(--transition-smooth) !important;
            font-size: 13px;
            color: #2c2013;
        }

        .admin-catalog-form .form-control:focus,
        .admin-catalog-form .bootstrap-select.show .dropdown-toggle {
            border-color: var(--catalog-theme) !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 3px var(--catalog-theme-glow) !important;
        }

        .admin-catalog-page .btn {
            border-radius: 999px !important;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 20px;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: none;
        }

        .admin-catalog-page .btn-primary {
            background: var(--catalog-theme) !important;
            border-color: var(--catalog-theme) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(181, 122, 69, 0.25) !important;
        }

        .admin-catalog-page .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(181, 122, 69, 0.35) !important;
        }

        .admin-catalog-page .btn-soft-primary {
            background: var(--catalog-theme-soft) !important;
            border-color: transparent !important;
            color: var(--catalog-theme) !important;
        }

        .admin-catalog-page .btn-soft-primary:hover {
            background: var(--catalog-theme) !important;
            color: #fff !important;
            transform: translateY(-1px);
        }

        .admin-catalog-page .btn-soft-danger {
            background: var(--catalog-danger-soft) !important;
            border-color: transparent !important;
            color: var(--catalog-danger) !important;
        }

        .admin-catalog-page .btn-soft-danger:hover {
            background: var(--catalog-danger) !important;
            color: #fff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(224, 79, 50, 0.2) !important;
        }

        .admin-catalog-help {
            margin-top: 6px;
            color: #8e8376;
            font-size: 12px;
            line-height: 1.45;
        }

        .admin-catalog-form .bootstrap-select,
        .admin-catalog-form .bootstrap-select>.dropdown-toggle {
            width: 100% !important;
        }

        .admin-catalog-form .bootstrap-select .dropdown-menu {
            z-index: 1060;
            max-width: 100%;
        }

        /* ── Attribute accordion card ──────────────────────────────── */
        .attr-editor-card {
            border: 1.5px solid #e8e4de;
            border-radius: var(--border-radius-md);
            background: #fff;
            margin-bottom: 20px;
            transition: var(--transition-smooth);
            box-shadow: 0 2px 10px rgba(44, 32, 19, 0.02);
        }

        .attr-editor-card:not(.is-collapsed) {
            border-color: var(--catalog-theme);
            box-shadow: 0 10px 30px rgba(181, 122, 69, 0.08);
        }

        .attr-editor-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px;
            cursor: pointer;
            background: #fbfbf9;
            border-bottom: 1.5px solid #e8e4de;
            transition: var(--transition-smooth);
        }

        .attr-editor-card.is-collapsed .attr-editor-head {
            border-bottom-left-radius: calc(var(--border-radius-md) - 1.5px);
            border-bottom-right-radius: calc(var(--border-radius-md) - 1.5px);
        }

        .attr-editor-card:not(.is-collapsed) .attr-editor-head {
            background: #fff;
            border-bottom-color: rgba(181, 122, 69, 0.15);
        }

        .attr-editor-head:hover {
            background: #f5f3ee;
        }

        .attr-title-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .attr-toggle-icon {
            width: 32px;
            height: 32px;
            border: 1.5px solid #ded6cd;
            border-radius: var(--border-radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: var(--catalog-theme);
            transition: var(--transition-smooth);
            flex-shrink: 0;
        }

        .attr-editor-card.is-collapsed .attr-toggle-icon {
            transform: rotate(-90deg);
            color: #8e8376;
            border-color: #e8e4de;
        }

        .attr-summary {
            color: #8e8376;
            font-size: 12px;
            font-weight: 600;
            margin-top: 2px;
        }

        .attr-editor-body {
            padding: 24px;
            background: #fff;
            border-bottom-left-radius: calc(var(--border-radius-md) - 1.5px);
            border-bottom-right-radius: calc(var(--border-radius-md) - 1.5px);
        }

        .attr-editor-card.is-collapsed .attr-editor-body {
            display: none;
        }

        /* ── Attribute values grid ──────────────────────────────────── */
        .attr-values-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1.5px solid #edf0f2;
        }

        .attr-values-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .attr-values-heading h6 {
            margin: 0;
            color: #2c2013;
            font-size: 14px;
            font-weight: 800;
        }

        .attr-value-heading,
        .attr-value-row {
            display: grid;
            grid-template-columns: minmax(220px, 2fr) minmax(220px, 1.2fr) 48px;
            gap: 16px;
            align-items: center;
        }

        .attr-value-heading {
            margin: 20px 0 10px;
            padding: 0 16px;
            color: #8e8376;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .attr-value-row {
            margin-bottom: 12px;
            padding: 16px;
            border: 1.5px solid #edf0f2;
            border-radius: var(--border-radius-md);
            background: #fbfbf9;
            transition: var(--transition-smooth);
        }

        .attr-value-row:hover {
            border-color: var(--catalog-theme);
            background: #fff;
            box-shadow: 0 4px 12px rgba(181, 122, 69, 0.05);
        }

        .attr-value-row .value-text {
            color: #2c2013;
            font-size: 13px;
            font-weight: 700;
        }

        .attr-value-image-field {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .attr-value-image {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            border: 1px solid #e8e4de;
            background: #f7f4ef;
            object-fit: cover;
            flex-shrink: 0;
        }

        .attr-value-image-empty {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #b8ada0;
            font-size: 16px;
        }

        .attr-value-image-input,
        .attr-new-image-input {
            font-size: 12px;
        }

        .attr-value-row .btn-remove-value {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            background: #e8e4de;
            color: #8e8376;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-smooth);
            font-size: 10px;
            padding: 0;
        }

        .attr-value-row .btn-remove-value:hover {
            background: var(--catalog-danger);
            color: #fff;
        }

        .attr-value-empty {
            padding: 20px;
            text-align: center;
            color: #8e8376;
            font-size: 13px;
            font-weight: 600;
            border: 1.5px dashed #ded6cd;
            border-radius: var(--border-radius-md);
            background: #fbfbf9;
            width: 100%;
        }

        .attr-add-value-row {
            grid-template-columns: minmax(220px, 1fr) minmax(220px, 0.6fr) minmax(150px, 150px);
            margin-top: 16px;
        }

        .attr-add-value-row .form-control {
            width: 100%;
        }

        .attr-add-value-row .attr-add-value-btn {
            width: 100%;
            height: 44px;
            justify-content: center;
            padding-left: 12px;
            padding-right: 12px;
            white-space: nowrap;
            line-height: 1.15;
        }

        .attr-image-save-note {
            display: block;
            margin-top: 6px;
            color: #8e8376;
            font-size: 11px;
            font-weight: 600;
        }

        /* ── Categories display inside accordion ──────────────────── */
        .attr-categories-section {
            margin-bottom: 20px;
        }

        .attr-categories-label {
            color: #8e8376;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        /* ── Add Attribute form card ──────────────────────────────── */
        .attr-add-form-card {
            margin-bottom: 24px;
        }

        .attr-add-form-card .attr-editor-head {
            background: var(--catalog-theme-soft);
            border-bottom-color: rgba(181, 122, 69, 0.15);
        }

        .attribute-form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .attribute-category-field {
            max-width: 100%;
        }

        .attribute-save-row {
            display: flex;
            justify-content: flex-end;
        }

        /* ── Responsive ─────────────────────────────────────────────── */
        @media (max-width: 991px) {
            .admin-catalog-header {
                align-items: stretch;
                flex-direction: column;
            }

            .attr-add-value-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .attr-value-heading {
                display: none;
            }

            .attr-value-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .attr-value-row>div::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 6px;
                color: #8e8376;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .attribute-save-row {
                justify-content: stretch;
            }

            .attribute-save-row .btn {
                width: 100%;
            }
        }

        /* ── Custom selectpicker active/hover state styling ─────────── */
        .admin-catalog-page .dropdown-menu .dropdown-item:hover,
        .admin-catalog-page .dropdown-menu .dropdown-item:focus,
        .admin-catalog-page .dropdown-menu .dropdown-item.active,
        .admin-catalog-page .dropdown-menu .dropdown-item.selected,
        .admin-catalog-page .dropdown-menu li a:hover,
        .admin-catalog-page .dropdown-menu li a:focus,
        .admin-catalog-page .dropdown-menu li.active a,
        .admin-catalog-page .dropdown-menu li.selected a {
            background-color: var(--catalog-theme) !important;
            color: #fff !important;
        }
    </style>

    <div class="admin-catalog-page mt-2">
        <div class="admin-catalog-header">
            <div>
                <div class="admin-catalog-eyebrow"><i class="las la-sliders-h" style="margin-right:4px;"></i>{{ translate('Catalog setup') }}</div>
                <h1 class="admin-catalog-title">{{ translate('My Custom Attributes') }}</h1>
                <p class="admin-catalog-subtitle">{{ translate('Create reusable product attributes, assign them to category branches, and manage their values — all from one place.') }}</p>
            </div>
            <span class="admin-catalog-chip"><i class="las la-tags" style="margin-right:4px;font-size:14px;"></i>{{ $attributes->count() }} {{ translate('attributes') }}</span>
        </div>

        {{-- ─── Add New Attribute Form ──────────────────────────────── --}}
        <div class="admin-catalog-card attr-add-form-card">
            <div class="attr-editor-head" style="cursor:default;">
                <div class="attr-title-wrap">
                    <span class="attr-toggle-icon" style="background:var(--catalog-theme-soft);border-color:var(--catalog-theme);color:var(--catalog-theme);">
                        <i class="las la-plus"></i>
                    </span>
                    <h5>{{ translate('Add New Attribute') }}</h5>
                </div>
            </div>
            <div class="admin-catalog-card-body">
                <form class="admin-catalog-form" action="{{ route('seller.attributes.store') }}" method="POST">
                    @csrf
                    <div class="attribute-form-grid">
                        <div>
                            <label for="name">{{ translate('Attribute Name') }}</label>
                            <input type="text" placeholder="{{ translate('Example: Material, Finish, Style') }}" id="name" name="name" class="form-control" required>
                            <div class="admin-catalog-help">{{ translate('You can use this attribute when creating your product variations.') }}</div>
                        </div>
                        <div class="attribute-category-field">
                            <label>{{ translate('Assign Categories') }}</label>
                            <select class="form-control aiz-selectpicker admin-category-cascade" name="categories[]" multiple data-live-search="true" data-selected-text-format="count" data-placeholder="{{ translate('Select Categories') }}" required>
                                {!! $renderCategoryOptions($categories) !!}
                            </select>
                            <div class="admin-catalog-help">{{ translate('Parent and child category selection stays in sync. Children can still be changed manually.') }}</div>
                        </div>
                        <div class="attribute-save-row">
                            <button type="submit" class="btn btn-primary px-4">{{ translate('Save Attribute') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── Attribute Library (accordion) ────────────────────────── --}}
        <div class="admin-catalog-card">
            <div class="admin-catalog-card-header">
                <h5>{{ translate('Attribute Library') }}</h5>
            </div>
            <div class="admin-catalog-card-body">
                <div id="attributes-wrapper">
                    @forelse ($attributes as $aIndex => $attribute)
                        <div class="attr-editor-card is-collapsed" data-attribute-id="{{ $attribute->id }}">
                            {{-- Accordion header --}}
                            <div class="attr-editor-head">
                                <div class="attr-title-wrap">
                                    <span class="attr-toggle-icon"><i class="las la-angle-down"></i></span>
                                    <div>
                                        <h5>{{ $attribute->getTranslation('name') }}</h5>
                                        <div class="attr-summary">
                                            <span class="attr-value-count">{{ $attribute->attribute_values->count() }}</span> {{ translate('values') }}
                                            · {{ $attribute->categories->count() }} {{ translate('categories') }}
                                        </div>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <a class="btn btn-soft-primary btn-sm" href="{{ route('seller.attributes.edit', ['id' => $attribute->id, 'lang' => env('DEFAULT_LANGUAGE', 'en')]) }}" title="{{ translate('Edit') }}" onclick="event.stopPropagation();">
                                        <i class="las la-edit"></i> {{ translate('Edit') }}
                                    </a>
                                    <a href="#" class="btn btn-soft-danger btn-sm confirm-delete" data-href="{{ route('seller.attributes.destroy', $attribute->id) }}" title="{{ translate('Delete') }}" onclick="event.stopPropagation();">
                                        <i class="las la-trash"></i>
                                    </a>
                                </div>
                            </div>

                            {{-- Accordion body --}}
                            <div class="attr-editor-body">
                                {{-- Categories display --}}
                                <div class="attr-categories-section">
                                    <div class="attr-categories-label">{{ translate('Assigned categories') }}</div>
                                    <div>
                                        @forelse ($attribute->categories->take(6) as $category)
                                            <span class="admin-catalog-chip">{{ $category->getTranslation('name') }}</span>
                                        @empty
                                            <span class="admin-catalog-chip admin-catalog-chip-muted">{{ translate('No category assigned') }}</span>
                                        @endforelse
                                        @if ($attribute->categories->count() > 6)
                                            <span class="admin-catalog-chip">+{{ $attribute->categories->count() - 6 }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Attribute values section --}}
                                <div class="attr-values-section">
                                    <div class="attr-values-heading">
                                        <h6><i class="las la-list-ul" style="margin-right:4px;"></i>{{ translate('Attribute Values') }}</h6>
                                    </div>
                                    <div class="attr-value-heading">
                                        <span>{{ translate('Option Name') }}</span>
                                        <span>{{ translate('Image') }}</span>
                                        <span></span>
                                    </div>
                                    <div class="attr-values-list" data-attribute-id="{{ $attribute->id }}">
                                        @forelse ($attribute->attribute_values as $value)
                                            <div class="attr-value-row" data-value-id="{{ $value->id }}">
                                                <div data-label="{{ translate('Option Name') }}">
                                                    <span class="value-text">{{ $value->value }}</span>
                                                </div>
                                                <div class="attr-value-image-field" data-label="{{ translate('Image') }}">
                                                    @if (!empty($value->image))
                                                        <img class="attr-value-image" src="{{ my_asset($value->image) }}" alt="{{ $value->value }}">
                                                    @else
                                                        <span class="attr-value-image attr-value-image-empty">
                                                            <i class="las la-image"></i>
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <input type="file" class="form-control attr-value-image-input" accept="image/*" data-value-id="{{ $value->id }}">
                                                        <span class="attr-image-save-note">{{ translate('Auto saves after choosing file') }}</span>
                                                    </div>
                                                </div>
                                                <div data-label="{{ translate('Remove') }}">
                                                    <button type="button" class="btn-remove-value" title="{{ translate('Remove value') }}" data-value-id="{{ $value->id }}">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="attr-value-empty">{{ translate('No values yet. Add values below.') }}</div>
                                        @endforelse
                                    </div>

                                    <div class="attr-value-row attr-add-value-row">
                                        <div data-label="{{ translate('Option Name') }}">
                                            <input type="text" class="form-control attr-new-value-input" placeholder="{{ translate('Type a value name and press Add...') }}" data-attribute-id="{{ $attribute->id }}">
                                        </div>
                                        <div data-label="{{ translate('Image') }}">
                                            <input type="file" class="form-control attr-new-image-input" accept="image/*">
                                        </div>
                                        <div data-label="{{ translate('Add') }}">
                                            <button type="button" class="btn btn-soft-primary btn-sm attr-add-value-btn" data-attribute-id="{{ $attribute->id }}">
                                                <i class="las la-plus"></i> {{ translate('Add Value') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="attr-value-empty" style="margin:12px 0;">
                            <i class="las la-info-circle" style="font-size:18px;margin-right:6px;"></i>
                            {{ translate('No attributes created yet. Use the form above to add your first attribute.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
        (function ($) {
            // ── Accordion toggle ────────────────────────────────────
            $(document).on('click', '.attr-editor-head', function (event) {
                if ($(event.target).closest('a, button, input, select, .bootstrap-select').length) return;
                $(this).closest('.attr-editor-card').toggleClass('is-collapsed');
            });

            // ── Category cascade sync (for add-form) ────────────────
            function syncCategoryCascade(select, clickedIndex, isSelected) {
                if (clickedIndex === undefined || clickedIndex === null) return;

                var option = select.find('option').eq(clickedIndex);
                var childrenIds = (option.attr('data-children-ids') || '').split(',').filter(Boolean);
                if (!childrenIds.length) return;

                var values = select.val() || [];

                if (isSelected) {
                    childrenIds.forEach(function (id) {
                        if (values.indexOf(id) === -1) {
                            values.push(id);
                        }
                    });
                } else {
                    values = values.filter(function (id) {
                        return childrenIds.indexOf(String(id)) === -1;
                    });
                }

                select.val(values);
                select.selectpicker('refresh');
            }

            $(document).on('changed.bs.select', '.admin-category-cascade', function (e, clickedIndex, isSelected) {
                syncCategoryCascade($(this), clickedIndex, isSelected);
            });

            // ── Add attribute value (AJAX) ──────────────────────────
            function addValue(attributeId, inputEl) {
                var value = inputEl.val().trim();
                if (!value) return;
                var rowEl = inputEl.closest('.attr-add-value-row');
                var imageInput = rowEl.find('.attr-new-image-input')[0];
                var formData = new FormData();
                formData.append('attribute_id', attributeId);
                formData.append('value', value);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                if (imageInput && imageInput.files && imageInput.files[0]) {
                    formData.append('image', imageInput.files[0]);
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route("seller.ajax.store-attribute-value") }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (resp) {
                        if (!resp.success) return;

                        var list = inputEl.closest('.attr-values-section').find('.attr-values-list');
                        list.find('.attr-value-empty').remove();

                        var row = $(
                            '<div class="attr-value-row" data-value-id="' + resp.id + '">' +
                                '<div data-label="{{ translate("Option Name") }}"><span class="value-text">' + $('<span>').text(resp.value).html() + '</span></div>' +
                                '<div class="attr-value-image-field" data-label="{{ translate("Image") }}">' +
                                    (resp.image_url ?
                                        '<img class="attr-value-image" src="' + $('<span>').text(resp.image_url).html() + '" alt="' + $('<span>').text(resp.value).html() + '">' :
                                        '<span class="attr-value-image attr-value-image-empty"><i class="las la-image"></i></span>'
                                    ) +
                                    '<div><input type="file" class="form-control attr-value-image-input" accept="image/*" data-value-id="' + resp.id + '">' +
                                    '<span class="attr-image-save-note">{{ translate("Auto saves after choosing file") }}</span></div>' +
                                '</div>' +
                                '<div data-label="{{ translate("Remove") }}"><button type="button" class="btn-remove-value" title="{{ translate("Remove value") }}" data-value-id="' +
                                    resp.id + '"><i class="las la-times"></i></button></div>' +
                            '</div>'
                        );
                        list.append(row);
                        inputEl.val('');
                        if (imageInput) imageInput.value = '';

                        // Update count in header
                        var card = inputEl.closest('.attr-editor-card');
                        var countEl = card.find('.attr-value-count');
                        countEl.text(parseInt(countEl.text()) + 1);
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.value) {
                            alert(xhr.responseJSON.errors.value[0]);
                        } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.image) {
                            alert(xhr.responseJSON.errors.image[0]);
                        }
                    }
                });
            }

            $(document).on('change', '.attr-value-image-input', function () {
                var input = this;
                var valueId = $(input).data('value-id');
                if (!input.files || !input.files[0]) return;

                var formData = new FormData();
                formData.append('image', input.files[0]);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    type: 'POST',
                    url: '/seller/ajax-update-attribute-value-image/' + valueId,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (resp) {
                        if (!resp.success) return;

                        var row = $(input).closest('.attr-value-row');
                        var imageHtml = '<img class="attr-value-image" src="' + $('<span>').text(resp.image_url).html() + '" alt="">';
                        row.find('.attr-value-image').first().replaceWith(imageHtml);
                        input.value = '';
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.image) {
                            alert(xhr.responseJSON.errors.image[0]);
                        }
                        input.value = '';
                    }
                });
            });

            $(document).on('click', '.attr-add-value-btn', function () {
                var attributeId = $(this).data('attribute-id');
                var inputEl = $(this).closest('.attr-add-value-row').find('.attr-new-value-input');
                addValue(attributeId, inputEl);
            });

            $(document).on('keypress', '.attr-new-value-input', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    var attributeId = $(this).data('attribute-id');
                    addValue(attributeId, $(this));
                }
            });

            // ── Remove attribute value (AJAX) ───────────────────────
            $(document).on('click', '.btn-remove-value', function () {
                var btn = $(this);
                var valueId = btn.data('value-id');
                var row = btn.closest('.attr-value-row');
                var card = btn.closest('.attr-editor-card');

                if (!confirm('{{ translate("Are you sure you want to remove this value?") }}')) return;

                $.ajax({
                    type: 'DELETE',
                    url: '/seller/ajax-destroy-attribute-value/' + valueId,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (resp) {
                        if (!resp.success) return;

                        row.remove();

                        // Update count
                        var countEl = card.find('.attr-value-count');
                        var newCount = Math.max(0, parseInt(countEl.text()) - 1);
                        countEl.text(newCount);

                        // Show empty message if no values left
                        var list = card.find('.attr-values-list');
                        if (list.find('.attr-value-row').length === 0) {
                            list.html('<div class="attr-value-empty">{{ translate("No values yet. Add values below.") }}</div>');
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
