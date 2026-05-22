@if ($shippingCharges->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
            <thead class="thead-light">
                <tr>
                    <th>{{ translate('Service Name') }}</th>
                    <th>{{ translate('Type') }}</th>
                    <th>{{ translate('Price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shippingCharges as $charge)
                    <tr>
                        <td>{{ $charge->name }}</td>
                        <td>
                            @if (isset($charge->type))
                                {{ ucfirst($charge->type) }}
                            @else
                                {{ translate('N/A') }}
                            @endif
                        </td>
                        <td>{{ single_price($charge->price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="col-12 p-0">
        <div class="alert alert-info mb-0">
            {{ translate('No shipping charges available for selected category.') }}
        </div>
    </div>
@endif
