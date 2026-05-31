@extends('backend.layouts.app')

@section('content')

<style>
    /* Premium visual styling for Offers Admin Listing */
    .theme-card {
        background-color: #faf8f5 !important;
        border: 1px solid #e5dec9 !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 30px rgba(104, 91, 78, 0.04) !important;
    }
    .theme-card-header {
        border-bottom: 1px solid #e5dec9 !important;
        background-color: transparent !important;
        padding: 1.5rem !important;
    }
    .theme-card-title {
        color: #39322a !important;
        font-weight: 700 !important;
        font-size: 1.05rem !important;
        display: flex;
        align-items: center;
    }
    .theme-card-title i {
        color: #685b4e !important;
        font-size: 1.3rem;
    }
    .theme-btn-primary {
        background-color: #685b4e !important;
        border: 1px solid #685b4e !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        padding: 10px 24px !important;
        border-radius: 50px !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
    }
    .theme-btn-primary:hover, .theme-btn-primary:focus {
        background-color: #554a3f !important;
        border-color: #554a3f !important;
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(104, 91, 78, 0.2) !important;
        transform: translateY(-1px) !important;
    }
    .theme-btn-secondary {
        background-color: #f3eee6 !important;
        border: 1px solid #dcd5c5 !important;
        color: #685b4e !important;
        font-weight: 700 !important;
        padding: 10px 24px !important;
        border-radius: 50px !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
    }
    .theme-btn-secondary:hover, .theme-btn-secondary:focus {
        background-color: #e8e1d5 !important;
        color: #554a3f !important;
        transform: translateY(-1px) !important;
    }
    .theme-table {
        border-collapse: separate !important;
        border-spacing: 0 8px !important;
        margin-top: 10px !important;
    }
    .theme-table thead tr th {
        color: #685b4e !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.8px !important;
        border-bottom: none !important;
        padding: 12px 16px !important;
    }
    .theme-table tbody tr {
        background-color: #ffffff !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 8px rgba(104, 91, 78, 0.02) !important;
    }
    .theme-table tbody tr:hover {
        background-color: #fcfbfa !important;
        box-shadow: 0 4px 12px rgba(104, 91, 78, 0.05) !important;
        transform: translateY(-1px);
    }
    .theme-table tbody td {
        border-top: 1px solid #f3eee6 !important;
        border-bottom: 1px solid #f3eee6 !important;
        padding: 16px !important;
        vertical-align: middle !important;
    }
    .theme-table tbody td:first-child {
        border-left: 1px solid #f3eee6 !important;
        border-top-left-radius: 12px !important;
        border-bottom-left-radius: 12px !important;
    }
    .theme-table tbody td:last-child {
        border-right: 1px solid #f3eee6 !important;
        border-top-right-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
    }
    
    /* Form controls and inputs */
    .theme-input {
        background-color: #ffffff !important;
        border: 1px solid #dcd5c5 !important;
        border-radius: 50px 0 0 50px !important;
        padding: 10px 20px !important;
        font-size: 0.9rem !important;
        color: #39322a !important;
        transition: all 0.2s ease !important;
        height: auto !important;
    }
    .theme-input:focus {
        border-color: #685b4e !important;
        box-shadow: 0 0 0 3px rgba(104, 91, 78, 0.1) !important;
        outline: none !important;
    }
    .theme-input-btn {
        background-color: #685b4e !important;
        border-color: #685b4e !important;
        color: #ffffff !important;
        border-radius: 0 50px 50px 0 !important;
        padding: 0 24px !important;
        font-weight: 700 !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .theme-input-btn:hover {
        background-color: #554a3f !important;
        border-color: #554a3f !important;
        color: #ffffff !important;
    }
    
    /* Overriding AIZ Switch and status indicators */
    .aiz-switch-success input:checked + span {
        background-color: #685b4e !important;
    }
    .aiz-switch input:checked + span {
        background-color: #685b4e !important;
    }
    
    .badge-soft-info-theme {
        background-color: #f3eee6 !important;
        color: #685b4e !important;
        border: 1px solid #e5dec9 !important;
    }
    .badge-soft-primary-theme {
        background-color: #fcfbfa !important;
        color: #5d5449 !important;
        border: 1px solid #e3dad0 !important;
    }
    .badge-soft-warning-theme {
        background-color: #fdf8e2 !important;
        color: #a1741e !important;
        border: 1px solid #f5e7c4 !important;
    }
    .badge-soft-danger-theme {
        background-color: #faebeb !important;
        color: #b73e3e !important;
        border: 1px solid #f5cccc !important;
    }
    
    /* Dropdown styling overrides to fix orange hover and transparent overlay bleed-through */
    .theme-table .dropdown-menu {
        background-color: #ffffff !important;
        border: 1px solid #e5dec9 !important;
        box-shadow: 0 10px 30px rgba(104, 91, 78, 0.12) !important;
        z-index: 999999 !important;
    }
    .theme-table .dropdown-item {
        color: #39322a !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
    }
    .theme-table .dropdown-item:hover, 
    .theme-table .dropdown-item:focus {
        background-color: #f3eee6 !important;
        color: #39322a !important;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6 col-12">
            <h1 class="h3 fw-700 text-dark">{{ translate('Offers & Dynamic Deals') }}</h1>
        </div>
        <div class="col-md-6 col-12 text-md-right mt-2 mt-md-0 d-flex justify-content-md-end style-gap-2 flex-wrap" style="gap: 10px;">
            <a href="{{ route('offers.seller_auto_approval') }}" class="theme-btn-secondary shadow-sm">
                <i class="las la-user-check mr-2 fs-16"></i>{{ translate('Seller Trusted Status') }}
            </a>
            <a href="{{ route('offers.create') }}" class="theme-btn-primary shadow-sm">
                <i class="las la-plus mr-2 fs-16"></i>{{ translate('Create Admin Offer') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Offers Table Card -->
    <div class="col-lg-12">
        <div class="card theme-card mb-4">
            <div class="card-header theme-card-header row align-items-center">
                <div class="col text-center text-md-left">
                    <h5 class="mb-0 theme-card-title"><i class="las la-percentage mr-2"></i>{{ translate('Deals & Offers Information') }}</h5>
                </div>
                <div class="col-md-4 mt-2 mt-md-0">
                    <form id="sort_offers" action="" method="GET">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control theme-input py-3" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="{{ translate('Search offer name & Enter') }}">
                            <div class="input-group-append">
                                <button class="btn theme-input-btn" type="submit">
                                    <i class="las la-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body py-4">
                @if($offers->count() > 0)
                    <div class="table-responsive">
                        <table class="table theme-table mb-0 table-hover">
                            <thead>
                                <tr class="text-uppercase fs-10 tracking-wider">
                                    <th>#</th>
                                    <th>{{ translate('Offer Details') }}</th>
                                    <th>{{ translate('Creator / Shop') }}</th>
                                    <th>{{ translate('Badge text') }}</th>
                                    <th>{{ translate('Discount') }}</th>
                                    <th>{{ translate('Validity Dates') }}</th>
                                    <th class="text-center">{{ translate('Priority') }}</th>
                                    <th>{{ translate('Home Display') }}</th>
                                    <th class="text-center">{{ translate('Active Status') }}</th>
                                    <th class="text-right" width="10%">{{ translate('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offers as $key => $offer)
                                    <tr style="transition: all 0.2s ease;">
                                        <td class="font-weight-bold">{{ ($key+1) + ($offers->currentPage() - 1) * $offers->perPage() }}</td>
                                        <td>
                                            <span class="d-block fw-700 text-dark fs-14">{{ translate($offer->name) }}</span>
                                            @if($offer->custom_text)
                                                <small class="text-muted text-truncate d-block" style="max-width: 250px;">{{ $offer->custom_text }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($offer->user)
                                                <span class="badge badge-inline badge-soft-info-theme px-2.5 py-1.5 rounded font-weight-bold"><i class="las la-user-tag mr-1"></i>{{ $offer->user->name }}</span>
                                                @if($offer->user->shop)
                                                    <br><small class="text-muted fs-11 mt-1 d-inline-block"><i class="las la-store mr-1"></i>{{ $offer->user->shop->name }}</small>
                                                @endif
                                            @else
                                                <span class="badge badge-inline badge-soft-primary-theme px-2.5 py-1.5 rounded font-weight-bold"><i class="las la-shield-alt mr-1"></i>{{ translate('Admin') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($offer->badge_text)
                                                {!! format_offer_badge($offer) !!}
                                            @else
                                                <span class="text-muted fs-12">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badge_info = get_offer_discount_badge_styles($offer->discount_type);
                                            @endphp
                                            <span class="badge badge-inline px-3 py-2 rounded-pill fw-700" style="background-color: {{ $badge_info['bg'] }}; color: {{ $badge_info['color'] }}; border: 1px solid {{ $badge_info['border'] }};">
                                                @if($offer->discount_type == 'percentage')
                                                    <i class="las la-percentage mr-1"></i>{{ $offer->discount_value }}% OFF
                                                @elseif($offer->discount_type == 'fixed')
                                                    <i class="las la-coins mr-1"></i>{{ single_price($offer->discount_value) }} OFF
                                                @else
                                                    <i class="las la-tag mr-1"></i>{{ translate('Badge Only') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <small class="d-block text-secondary fw-500">
                                                <i class="las la-clock mr-1"></i>{{ translate('Start') }}: <span class="text-dark">{{ $offer->starts_at ? $offer->starts_at->format('d-m-Y H:i') : translate('N/A') }}</span>
                                            </small>
                                            <small class="d-block text-secondary fw-500 mt-1">
                                                <i class="las la-clock mr-1"></i>{{ translate('End') }}: <span class="text-dark">{{ $offer->ends_at ? $offer->ends_at->format('d-m-Y H:i') : translate('N/A') }}</span>
                                            </small>
                                        </td>
                                        <td class="text-center fw-700 text-dark">{{ $offer->priority }}</td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input onchange="toggle_home_display(this)" value="{{ $offer->id }}" type="checkbox" @if($offer->show_on_home == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            @if($offer->status == 'pending')
                                                <span class="badge badge-inline badge-soft-warning-theme px-2.5 py-1.5 rounded fw-600"><i class="las la-hourglass-half mr-1"></i>{{ translate('Pending') }}</span>
                                            @elseif($offer->status == 'rejected')
                                                <span class="badge badge-inline badge-soft-danger-theme px-2.5 py-1.5 rounded fw-600"><i class="las la-times mr-1"></i>{{ translate('Rejected') }}</span>
                                            @else
                                                <label class="aiz-switch aiz-switch-success mb-0" title="{{ translate('Toggle Active / Inactive') }}">
                                                    <input onchange="toggle_active_status(this)" value="{{ $offer->id }}" type="checkbox" @if($offer->status == 'approved') checked @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-icon btn-circle btn-sm shadow-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="las la-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right rounded-lg border-0 shadow-lg py-2">
                                                    @if($offer->status == 'pending')
                                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="update_offer_status({{ $offer->id }}, 'approved')">
                                                            <i class="las la-check-circle text-success mr-2 fs-16"></i>{{ translate('Approve') }}
                                                        </a>
                                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="update_offer_status({{ $offer->id }}, 'rejected')">
                                                            <i class="las la-times-circle text-danger mr-2 fs-16"></i>{{ translate('Reject') }}
                                                        </a>
                                                    @elseif($offer->status == 'approved')
                                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="update_offer_status({{ $offer->id }}, 'inactive')">
                                                            <i class="las la-ban text-secondary mr-2 fs-16"></i>{{ translate('Deactivate') }}
                                                        </a>
                                                    @else
                                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="update_offer_status({{ $offer->id }}, 'approved')">
                                                            <i class="las la-check-circle text-success mr-2 fs-16"></i>{{ translate('Activate / Approve') }}
                                                        </a>
                                                    @endif
                                                    
                                                    <div class="dropdown-divider border-gray-100"></div>

                                                    <a class="dropdown-item py-2" href="{{ route('offers.edit', $offer->id) }}">
                                                        <i class="las la-edit text-primary mr-2 fs-16"></i>{{ translate('Edit') }}
                                                    </a>
                                                    <a class="dropdown-item py-2 confirm-delete" href="javascript:void(0)" data-href="{{ route('offers.destroy', $offer->id) }}">
                                                        <i class="las la-trash text-danger mr-2 fs-16"></i>{{ translate('Delete') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix mt-4">
                        <div class="pull-right">
                            {{ $offers->appends(request()->input())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="{{ static_asset('assets/img/nothing.png') }}" class="mw-100 mb-3" style="height: 120px;" alt="No data found">
                        <p class="text-muted fw-600 fs-16">{{ translate('No offers or deals found.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function toggle_home_display(el) {
        var show_on_home = el.checked ? 1 : 0;
        $.post('{{ route('offers.update_home_toggle') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            show_on_home: show_on_home
        }, function(data) {
            if (data.success) {
                AIZ.plugins.notify('success', data.message);
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }

    function toggle_active_status(el) {
        var status = el.checked ? 'approved' : 'inactive';
        $.post('{{ route('offers.update_status') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        }, function(data) {
            if (data.success) {
                AIZ.plugins.notify('success', data.message);
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }

    function update_offer_status(id, status) {
        $.post('{{ route('offers.update_status') }}', {
            _token: '{{ csrf_token() }}',
            id: id,
            status: status
        }, function(data) {
            if (data.success) {
                AIZ.plugins.notify('success', data.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>
@endsection
