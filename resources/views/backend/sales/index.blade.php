@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Orders') }}</h5>
                </div>

                @can('delete_order')
                    <div class="dropdown mb-2 mb-md-0">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal">
                                {{ translate('Delete selection') }}</a>
                        </div>
                    </div>
                @endcan

                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                        <option value="">{{ translate('Filter by Delivery Status') }}</option>
                        <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{ translate('Pending') }}
                        </option>
                        <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                            {{ translate('Confirmed') }}</option>
                        <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                            {{ translate('Picked Up') }}</option>
                        <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                            {{ translate('On The Way') }}</option>
                        <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                            {{ translate('Delivered') }}</option>
                        <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                            {{ translate('Cancel') }}</option>
                    </select>
                </div>
                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid"
                            @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>
                            {{ translate('Paid') }}</option>
                        <option value="unpaid"
                            @isset($payment_status) @if ($payment_status == 'unpaid') selected @endif @endisset>
                            {{ translate('Unpaid') }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                            name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <!--<th>#</th>-->
                            @if (auth()->user()->can('delete_order'))
                                <th>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-all">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </th>
                            @else
                                <th data-breakpoints="lg">#</th>
                            @endif

                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="md">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="md">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Seller') }}</th>
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment method') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                            @if (addon_is_activated('refund_request'))
                                <th>{{ translate('Refund') }}</th>
                            @endif
                            <th class="text-right" width="15%">{{ translate('options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                @if (auth()->user()->can('delete_order'))
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $order->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                @endif
                                <td>
                                    {{ $order->code }}
                                    @if ($order->viewed == 0)
                                        <span class="badge badge-inline badge-info">{{ translate('New') }}</span>
                                    @endif
                                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                        <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td>
                                    @if ($order->user != null)
                                        {{ $order->user->name }}
                                    @else
                                        Guest ({{ $order->guest_id }})
                                    @endif
                                </td>
                                <td>
                                    @if ($order->shop)
                                        {{ $order->shop->name }}
                                    @else
                                        {{ translate('Inhouse Order') }}
                                    @endif
                                </td>
                                <td>
                                    {{ single_price($order->grand_total) }}
                                </td>
                                <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                                </td>
                                <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                                </td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                    @endif
                                </td>
                                @if (addon_is_activated('refund_request'))
                                    <td>
                                        @if (count($order->refund_requests) > 0)
                                            {{ count($order->refund_requests) }} {{ translate('Refund') }}
                                        @else
                                            {{ translate('No Refund') }}
                                        @endif
                                    </td>
                                @endif
                                <td class="text-right">
                                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            href="{{ route('admin.invoice.thermal_printer', $order->id) }}" target="_blank"
                                            title="{{ translate('Thermal Printer') }}">
                                            <i class="las la-print"></i>
                                        </a>
                                    @endif
                                    @can('view_order_details')
                                        @php
                                            $order_detail_route = route('orders.show', encrypt($order->id));
                                            if (Route::currentRouteName() == 'seller_orders.index') {
                                                $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                            } elseif (Route::currentRouteName() == 'pick_up_point.index') {
                                                $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                            }
                                            if (Route::currentRouteName() == 'inhouse_orders.index') {
                                                $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                            }
                                        @endphp
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ $order_detail_route }}" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endcan
                                    
                                   @if ($order->payment_status == 'paid')
                                    <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                        href="{{ route('invoice.download', $order->id) }}"
                                        title="{{ translate('Download Invoice') }}">
                                        <i class="las la-download"></i>
                                    </a>
                                    @endif
                                    
                                    @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                        <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0 confirm-delete" data-href="{{route('purchase_history.destroy', $order->id)}}" title="{{ translate('Cancel') }}">
                                            <!--<svg xmlns="http://www.w3.org/2000/svg" width="9.202" height="12" viewBox="0 0 9.202 12">-->
                                            <!--    <path id="Path_28714" data-name="Path 28714" d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z" transform="translate(-6.478 -3.322)" fill="#d43533"/>-->
                                            <!--</svg>-->
                                            <!--<i class="las la-times" style="font-size: 20px;"></i>-->
                                            <i class="las la-times" style="color: #d43533; font-size: 18px;"></i>

                                        </a>
                                    @endif
                                    
                                    
                                    @can('delete_order')
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('orders.destroy', $order->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="aiz-pagination">
                    {{ $orders->appends(request()->input())->links() }}
                </div>

            </div>
        </form>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
@endsection
<style>
   table td {
    font-size: 12px !important;
}
.aiz-table thead th{
    font-size: 14px !important;
    font-weight: 600;
    white-space: nowrap;
}
.currency {
    font-size: 14px !important;
    display: inline !important;
}
</style>

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        //        function change_status() {
        //            var data = new FormData($('#order_form')[0]);
        //            $.ajax({
        //                headers: {
        //                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                },
        //                url: "{{ route('bulk-order-status') }}",
        //                type: 'POST',
        //                data: data,
        //                cache: false,
        //                contentType: false,
        //                processData: false,
        //                success: function (response) {
        //                    if(response == 1) {
        //                        location.reload();
        //                    }
        //                }
        //            });
        //        }
        
        

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
