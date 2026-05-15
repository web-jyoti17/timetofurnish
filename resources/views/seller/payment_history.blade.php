@extends('seller.layouts.app')

@section('panel_content')
      <div class="card">
        <!-- Filter Form -->
        <form action="" id="sort_payment_history" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Payment History') }}</h5>
                </div>
                <div class="col-lg-3">
                    <div class="form-group mb-0">
                        <input type="text" 
                               class="form-control form-control-sm aiz-date-range" 
                               id="search" 
                               name="date_range"
                               @isset($date_range) value="{{ $date_range }}" @endisset 
                               placeholder="{{ translate('Date Range') }}" 
                               autocomplete="off">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary btn-sm">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </div>
        </form>
        @if (count($payments) > 0)
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Date')}}</th>
                            <th>{{ translate('Amount')}}</th>
                            <th>{{ translate('Payment Method')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $key => $payment)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                <td>
                                    {{ single_price($payment->amount) }}
                                </td>
                                <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $payment->payment_method))) }} @if ($payment->txn_code != null) ({{  translate('TRX ID') }} : {{ $payment->txn_code }}) @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                  {{ $payments->appends(request()->query())->links() }}

              	</div>
            </div>
        @endif
    </div>

@endsection

