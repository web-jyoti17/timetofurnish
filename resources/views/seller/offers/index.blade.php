@extends('seller.layouts.app')

@section('panel_content')

<style>
    /* Premium visual styling for Offers Seller Listing */
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
    
    /* Overriding AIZ Switch and status indicators */
    .aiz-switch-success input:checked + span {
        background-color: #685b4e !important;
    }
    .aiz-switch input:checked + span {
        background-color: #685b4e !important;
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
</style>

<div class="aiz-titlebar text-left mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6 col-12">
            <h1 class="h3 fw-700 text-dark">{{ translate('My Offers & Dynamic Deals') }}</h1>
        </div>
        <div class="col-md-6 col-12 text-md-right mt-2 mt-md-0">
            <a href="{{ route('seller.offers.create') }}" class="theme-btn-primary shadow-sm">
                <i class="las la-plus mr-2 fs-16"></i>{{ translate('Create New Offer') }}
            </a>
        </div>
    </div>
</div>

<div class="card theme-card mb-4">
    <div class="card-header theme-card-header">
        <h5 class="mb-0 theme-card-title"><i class="las la-percentage mr-2"></i>{{ translate('All My Active Offers') }}</h5>
    </div>
    <div class="card-body py-4">
        @if($offers->count() > 0)
            <div class="table-responsive">
                <table class="table theme-table mb-0 table-hover">
                    <thead>
                        <tr class="text-uppercase fs-10 tracking-wider">
                            <th>#</th>
                            <th>{{ translate('Offer Details') }}</th>
                            <th>{{ translate('Badge Text') }}</th>
                            <th>{{ translate('Discount') }}</th>
                            <th>{{ translate('Validity Dates') }}</th>
                            <th class="text-center">{{ translate('Active Status') }}</th>
                            <th class="text-right" width="12%">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $key => $offer)
                            <tr style="transition: all 0.2s ease;">
                                <td class="font-weight-bold">{{ ($key+1) + ($offers->currentPage() - 1)*$offers->perPage() }}</td>
                                <td>
                                    <span class="d-block fw-700 text-dark fs-14">{{ translate($offer->name) }}</span>
                                    @if($offer->custom_text)
                                        <small class="text-muted text-truncate d-block" style="max-width: 250px;">{{ $offer->custom_text }}</small>
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
                                <td class="text-center">
                                    @if($offer->status == 'pending')
                                        <span class="badge badge-inline badge-soft-warning-theme px-2.5 py-1.5 rounded fw-600"><i class="las la-hourglass-half mr-1"></i>{{ translate('Pending Approval') }}</span>
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
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm shadow-sm" href="{{ route('seller.offers.edit', $offer->id) }}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm shadow-sm confirm-delete" data-href="{{ route('seller.offers.destroy', $offer->id) }}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="clearfix mt-4">
                <div class="pull-right">
                    {{ $offers->links() }}
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

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function toggle_active_status(el) {
        var status = el.checked ? 'approved' : 'inactive';
        $.post('{{ route('seller.offers.update_status') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        }, function(data) {
            if (data.success) {
                AIZ.plugins.notify('success', data.message);
                if (data.status === 'pending') {
                    // If not auto-approved, it goes to pending and requires admin re-approval
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>
@endsection
