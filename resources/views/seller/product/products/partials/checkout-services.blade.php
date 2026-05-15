@if($services->count() > 0)
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
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->name }}</td>
                        <td>{{ ucfirst($service->type) }}</td>
                        <td>
                            @if($service->price)
                                £{{ number_format($service->price, 2) }}
                            @else
                                {{ translate('Free') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="col-12">
        <div class="alert alert-warning mb-0">
            {{ translate('No delivery or assembly services available for selected category.') }}
        </div>
    </div>
@endif
