@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Shipping Charges') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('All Shipping Charges') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Price') }}</th>
                                <th>{{ translate('Categories') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shippingCharges as $key => $charge)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $charge->name }}</td>
                                    <td>{{ single_price($charge->price) }}</td>
                                    <td class="admin-table-categories">
                                        <div class="admin-category-badges">
                                            @foreach ($charge->categories as $catIndex => $cat)
                                                <span class="badge badge-inline badge-md bg-soft-dark admin-category-badge {{ $catIndex >= 8 ? 'd-none extra-category-badge' : '' }}">{{ $cat->getTranslation('name') }}</span>
                                            @endforeach
                                        </div>
                                        @if ($charge->categories->count() > 8)
                                            <button type="button" class="btn btn-link btn-xs p-0 admin-badges-read-more">
                                                {{ translate('Read more') }}
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox"
                                                onchange="update_shipping_charge_status(this, {{ $charge->id }})"
                                                {{ $charge->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('shipping-charges.edit', $charge->id) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <form action="{{ route('shipping-charges.destroy', $charge->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="return confirm('{{ translate('Are you sure you want to delete this shipping charge?') }}');" title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">
                        {{ isset($shippingCharge) ? translate('Edit Shipping Charge') : translate('Add New Shipping Charge') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($shippingCharge) ? route('shipping-charges.update', $shippingCharge->id) : route('shipping-charges.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name"><b>{{ translate('Name') }}</b></label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', isset($shippingCharge) ? $shippingCharge->name : '') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price"><b>{{ translate('Shipping Charge') }}</b></label>
                            <input type="number" step="0.01" min="0" id="price" name="price" class="form-control"
                                value="{{ old('price', isset($shippingCharge) ? $shippingCharge->price : '') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description"><b>{{ translate('Description') }}</b></label>
                            <textarea name="description" id="description" class="form-control">{{ old('description', isset($shippingCharge) ? $shippingCharge->description : '') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="mb-0"><b>{{ translate('Categories') }}</b></label>
                                <div>
                                    <button type="button" class="btn btn-soft-primary btn-xs" id="select-all-shipping-categories">{{ translate('Select All') }}</button>
                                    <button type="button" class="btn btn-soft-secondary btn-xs" id="deselect-all-shipping-categories">{{ translate('Deselect All') }}</button>
                                </div>
                            </div>
                            @php
                                $selectedCats = old('categories', isset($shippingCharge) ? $shippingCharge->categories->pluck('id')->toArray() : []);
                            @endphp
                            <div class="admin-category-picker" id="shipping-category-picker">
                                @foreach($categories as $category)
                                    @include('backend.shippingCharges.partials.category-checkbox', [
                                        'category' => $category,
                                        'selectedCats' => $selectedCats,
                                        'level' => 0
                                    ])
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-link btn-sm px-0 admin-category-read-more" data-target="#shipping-category-picker">
                                {{ translate('Read more') }}
                            </button>
                        </div>

                        <div class="form-group mb-3">
                            <label for="sort_order"><b>{{ translate('Sort Order') }}</b></label>
                            <input type="number" id="sort_order" name="sort_order" class="form-control"
                                value="{{ old('sort_order', isset($shippingCharge) ? $shippingCharge->sort_order : 0) }}" min="0">
                        </div>

                        <div class="form-group mb-3">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="status" value="1"
                                    {{ old('status', isset($shippingCharge) ? $shippingCharge->status : 1) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span class="ml-2">{{ translate('Enable Shipping Charge') }}</span>
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function update_shipping_charge_status(el, id) {
            var payload = new URLSearchParams();
            payload.append('_token', '{{ csrf_token() }}');
            payload.append('status', el.checked ? 1 : 0);

            fetch('{{ url('admin/shipping-charges') }}/' + id + '/update', {
                method: 'POST',
                headers: {
                    'Accept': 'text/plain',
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: payload.toString()
            }).then(function(response) {
                return response.text();
            }).then(function(data) {
                var success = data.trim() == '1';
                AIZ.plugins.notify(success ? 'success' : 'danger', success
                    ? '{{ translate('Shipping charge status updated successfully') }}'
                    : '{{ translate('Something went wrong') }}');
            });
        }

        (function() {
            var checkboxSelector = '.shipping-category-checkbox';
            var childContainerSelector = '.shipping-category-children';
            var toggleSelector = '.shipping-category-toggle';

            function childCheckboxes(parentId) {
                return Array.prototype.filter.call(document.querySelectorAll(checkboxSelector), function(checkbox) {
                    return checkbox.getAttribute('data-parent-id') === String(parentId);
                });
            }

            function childContainers(parentId) {
                return Array.prototype.filter.call(document.querySelectorAll(childContainerSelector), function(container) {
                    return container.getAttribute('data-parent-id') === String(parentId);
                });
            }

            function setDescendants(parentId, checked) {
                childCheckboxes(parentId).forEach(function(checkbox) {
                    checkbox.checked = checked;
                    setDescendants(checkbox.getAttribute('data-category-id'), checked);
                });
            }

            document.addEventListener('click', function(event) {
                if (event.target.id === 'select-all-shipping-categories') {
                    document.querySelectorAll(checkboxSelector).forEach(function(checkbox) {
                        checkbox.checked = true;
                    });
                }

                if (event.target.id === 'deselect-all-shipping-categories') {
                    document.querySelectorAll(checkboxSelector).forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                }

                if (event.target.matches('.admin-category-read-more')) {
                    var picker = document.querySelector(event.target.getAttribute('data-target'));
                    if (picker) {
                        picker.classList.toggle('is-expanded');
                        event.target.textContent = picker.classList.contains('is-expanded')
                            ? '{{ translate('Show less') }}'
                            : '{{ translate('Read more') }}';
                    }
                }

                if (event.target.matches('.admin-badges-read-more')) {
                    var cell = event.target.closest('.admin-table-categories');
                    if (cell) {
                        var isExpanded = cell.classList.toggle('is-expanded');
                        cell.querySelectorAll('.extra-category-badge').forEach(function(badge) {
                            badge.classList.toggle('d-none', !isExpanded);
                        });
                        event.target.textContent = isExpanded
                            ? '{{ translate('Show less') }}'
                            : '{{ translate('Read more') }}';
                    }
                }

                if (event.target.matches(toggleSelector)) {
                    var categoryId = event.target.getAttribute('data-category-id');
                    var isExpanded = event.target.getAttribute('aria-expanded') === 'true';
                    var nextExpanded = !isExpanded;

                    event.target.setAttribute('aria-expanded', nextExpanded ? 'true' : 'false');
                    event.target.textContent = nextExpanded ? '−' : '+';

                    childContainers(categoryId).forEach(function(container) {
                        container.style.display = nextExpanded ? '' : 'none';
                    });

                    if (nextExpanded) {
                        var parentCheckbox = document.querySelector(checkboxSelector + '[data-category-id="' + categoryId + '"]');
                        if (parentCheckbox) {
                            parentCheckbox.checked = true;
                        }
                        setDescendants(categoryId, true);
                    }
                }
            });

            document.addEventListener('change', function(event) {
                if (event.target.matches(checkboxSelector)) {
                    setDescendants(event.target.getAttribute('data-category-id'), event.target.checked);
                }
            });
        })();
    </script>
    <style>
        .admin-category-picker {
            max-height: 260px;
            overflow: hidden;
            border: 1px solid #e8e8e8;
            border-radius: 6px;
            padding: 12px;
            background: #fff;
        }

        .admin-category-picker.is-expanded {
            max-height: none;
            overflow: visible;
        }

        .shipping-category-row label {
            line-height: 1.35;
        }

        .admin-table-categories {
            max-width: 420px;
        }

        .admin-category-badges {
            max-height: 76px;
            overflow: hidden;
        }

        .admin-table-categories.is-expanded .admin-category-badges {
            max-height: none;
            overflow: visible;
        }

        .admin-category-badge {
            margin: 0 4px 6px 0;
            line-height: 1.4;
        }
    </style>
@endsection
