@extends('backend.layouts.app')

@section('content')

<style>
    /* Premium visual styling for Seller Trusted Status */
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
    
    /* Overriding AIZ Switch */
    .aiz-switch-success input:checked + span {
        background-color: #685b4e !important;
    }
    .aiz-switch input:checked + span {
        background-color: #685b4e !important;
    }
    
    .avatar-theme {
        background-color: #f3eee6 !important;
        color: #685b4e !important;
        border: 1px solid #e5dec9 !important;
    }
    .badge-soft-info-theme {
        background-color: #fcfbfa !important;
        color: #5d5449 !important;
        border: 1px solid #e3dad0 !important;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 fw-700 text-dark">{{ translate('Seller Trusted Auto-Approval Status') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('offers.index') }}" class="theme-btn-primary shadow-sm">
                <i class="las la-tags mr-2 fs-16"></i>{{ translate('Manage Offers & Deals') }}
            </a>
        </div>
    </div>
</div>

<div class="card theme-card mb-4">
    <div class="card-header theme-card-header row align-items-center">
        <div class="col text-center text-md-left">
            <h5 class="mb-0 theme-card-title"><i class="las la-user-check mr-2"></i>{{ translate('Seller Trusted Status Management') }}</h5>
        </div>
        <div class="col-md-4 mt-2 mt-md-0">
            <form id="search_sellers" action="" method="GET">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control theme-input py-3" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="{{ translate('Search seller name or email...') }}">
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
        @if($sellers->count() > 0)
            <div class="table-responsive">
                <table class="table theme-table mb-0 table-hover">
                    <thead>
                        <tr class="text-uppercase fs-10 tracking-wider">
                            <th>#</th>
                            <th>{{ translate('Seller Details') }}</th>
                            <th>{{ translate('Shop Name') }}</th>
                            <th>{{ translate('Auto-Approve Offers') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sellers as $key => $seller)
                            <tr style="transition: all 0.2s ease;">
                                <td class="font-weight-bold">{{ ($key+1) + ($sellers->currentPage() - 1) * $sellers->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm avatar-theme rounded-circle mr-3 fw-700 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 14px;">
                                            {{ strtoupper(substr($seller->name, 0, 2)) }}
                                        </span>
                                        <div>
                                            <span class="d-block fw-700 text-dark">{{ $seller->name }}</span>
                                            <small class="text-muted"><i class="las la-envelope mr-1"></i>{{ $seller->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($seller->shop)
                                        <span class="badge badge-inline badge-soft-info-theme px-3 py-2 rounded-pill fw-600">
                                            <i class="las la-store-alt mr-1"></i>{{ $seller->shop->name }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-12">{{ translate('N/A') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="toggle_auto_approve(this)" value="{{ $seller->id }}" type="checkbox" @if($seller->auto_approve_offers == 1) checked @endif>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="clearfix mt-4">
                <div class="pull-right">
                    {{ $sellers->appends(request()->input())->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <img src="{{ static_asset('assets/img/nothing.png') }}" class="mw-100 mb-3" style="height: 120px;" alt="No data found">
                <p class="text-muted fw-600 fs-16">{{ translate('No sellers found with current search.') }}</p>
            </div>
        @endif
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    function toggle_auto_approve(el) {
        var auto_approve_offers = el.checked ? 1 : 0;
        $.post('{{ route('sellers.toggle_auto_approve') }}', {
            _token: '{{ csrf_token() }}',
            user_id: el.value,
            auto_approve_offers: auto_approve_offers
        }, function(data) {
            if (data.success) {
                AIZ.plugins.notify('success', data.message);
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>
@endsection
