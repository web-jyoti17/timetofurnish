<form action="{{ route('commission-log.index') }}" method="GET">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('Commission History') }}</h5>
        </div>

        @if(Auth::user()->user_type != 'seller')
            <div class="col-md-3 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0"
                        name="seller_id"
                        data-live-search="true">
                    <option value="">{{ translate('Choose Seller') }}</option>
                    @foreach (App\Models\User::where('user_type','seller')->get() as $seller)
                        <option value="{{ $seller->id }}"
                            @if(isset($seller_id) && $seller_id == $seller->id) selected @endif>
                            {{ $seller->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-3">
            <input type="text"
                   class="form-control form-control-sm aiz-date-range"
                   name="date_range"
                   placeholder="{{ translate('Daterange') }}"
                   @isset($date_range) value="{{ $date_range }}" @endisset>
        </div>

        <div class="col-md-2">
            <button class="btn btn-sm btn-primary" type="submit">
                {{ translate('Filter') }}
            </button>
        </div>
    </div>
</form>

<div class="card-body">
    <table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th data-breakpoints="lg">{{ translate('Order Code') }}</th>
                <th>{{ translate('Seller') }}</th>
                <th>{{ translate('Admin Commission') }}</th>
                <th>{{ translate('Seller Earning') }}</th>
                <th data-breakpoints="lg">{{ translate('Created At') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($commission_history as $key => $history)
                <tr>
                    <td>{{ ($key + 1) + ($commission_history->currentPage() - 1) * $commission_history->perPage() }}</td>

                    <td>
                        @if($history->order)
                            {{ $history->order->code }}
                        @else
                            <span class="badge badge-danger">{{ translate('Order Deleted') }}</span>
                        @endif
                    </td>

                    <td>
                        @if($history->seller)
                            {{ $history->seller->name }} <br>
                            <small class="text-muted">{{ $history->seller->email }}</small>
                        @else
                            <span class="badge badge-warning">{{ translate('Seller Not Found') }}</span>
                        @endif
                    </td>

                    <td>{{ single_price($history->admin_commission) }}</td>

                    <td>{{ single_price($history->seller_earning) }}</td>

                    <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="aiz-pagination mt-4">
        {{ $commission_history->appends(request()->input())->links() }}
    </div>
</div>
