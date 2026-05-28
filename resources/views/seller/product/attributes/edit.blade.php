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
                $prefix = str_repeat('&nbsp;&nbsp;&nbsp;', $depth);
                echo '<option value="' . $category->id . '" data-children-ids="' . implode(',', $childrenIds) . '"' . (in_array($category->id, $selected) ? ' selected' : '') . '>' . $prefix . e($category->getTranslation('name')) . '</option>';
                $renderCategoryOptions($category->childrenCategories, $selected, $depth + 1);
            }
        };
    @endphp

    <style>
        .admin-pol-page {
            max-width: 900px;
            margin: 0 auto;
            font-family: 'Public Sans', sans-serif;
        }

        .admin-pol-title {
            margin: 14px 0 18px;
            color: #202223;
            font-size: 24px;
            font-weight: 700;
        }

        .admin-pol-card {
            border: 1px solid #dfe3e8;
            border-radius: 8px;
            background: #fff;
            box-shadow: none;
        }

        .admin-pol-tabs {
            border-bottom: 1px solid #dfe3e8;
        }

        .admin-pol-tabs .nav-link {
            border: 0;
            border-bottom: 2px solid transparent;
            color: #6d7175;
            font-weight: 700;
            padding: 14px 16px;
        }

        .admin-pol-tabs .nav-link.active {
            border-bottom-color: #c59259;
            color: #202223;
            background: transparent;
        }

        .admin-pol-form {
            padding: 22px;
        }

        .admin-pol-form label {
            color: #202223;
            font-size: 13px;
            font-weight: 700;
        }

        .admin-pol-form .form-control,
        .admin-pol-form .bootstrap-select .dropdown-toggle {
            min-height: 40px;
            border-color: #c9cccf;
            border-radius: 6px;
            box-shadow: none;
        }

        .admin-pol-help {
            margin-top: 6px;
            color: #6d7175;
            font-size: 12px;
            line-height: 1.45;
        }
    </style>

    <div class="admin-pol-page mt-2">
        <h1 class="admin-pol-title">{{ translate('Edit Attribute') }}</h1>

        <div class="admin-pol-card">
            <ul class="nav nav-tabs nav-fill admin-pol-tabs">
                @foreach (get_all_active_language() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link @if ($language->code == $lang) active @endif"
                            href="{{ route('seller.attributes.edit', ['id' => $attribute->id, 'lang' => $language->code]) }}">
                            <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
                                class="mr-1">
                            <span>{{ $language->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <form class="admin-pol-form" action="{{ route('seller.attributes.update', $attribute->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{ translate('Name') }} <i
                            class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                            class="form-control" required value="{{ $attribute->getTranslation('name', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{ translate('Categories') }}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker admin-category-cascade" name="categories[]"
                            multiple data-live-search="true" data-selected-text-format="count"
                            data-placeholder="{{ translate('Select categories') }}" required>
                            {!! $renderCategoryOptions($categories, $AttributeCategory) !!}
                        </select>
                        <div class="admin-pol-help">{{ translate('Selecting a parent automatically selects its subcategories. You can deselect any child category after that.') }}</div>
                    </div>
                </div>

                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function ($) {
            $(document).on('changed.bs.select', '.admin-category-cascade', function (e, clickedIndex, isSelected) {
                if (clickedIndex === undefined || clickedIndex === null || !isSelected) return;

                var select = $(this);
                var option = select.find('option').eq(clickedIndex);
                var childrenIds = (option.attr('data-children-ids') || '').split(',').filter(Boolean);
                if (!childrenIds.length) return;

                var currentValues = select.val() || [];
                childrenIds.forEach(function (id) {
                    if (currentValues.indexOf(id) === -1) {
                        currentValues.push(id);
                    }
                });

                select.val(currentValues);
                select.selectpicker('refresh');
            });
        })(jQuery);
    </script>
@endsection
