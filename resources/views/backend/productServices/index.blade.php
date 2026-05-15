@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('All Services') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Services') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Type') }}</th>
                                <th>{{ translate('Price') }}</th>
                                <th>{{ translate('Categories') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $key => $serv)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $serv->name }}</td>
                                    <td>{{ ucfirst($serv->type) }}</td>
                                    <td>{{ single_price($serv->price) }}</td>
                                    <td>
                                        @foreach ($serv->categories as $cat)
                                            <span class="badge badge-inline badge-md bg-soft-dark">{{ $cat->getTranslation('name') }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox"
                                                onchange="update_service_status(this, {{ $serv->id }})"
                                                {{ $serv->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('services.edit', $serv->id) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <form action="{{ route('services.destroy', $serv->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="return confirm('{{ translate('Are you sure you want to delete this service?') }}');" title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Pagination could be added here if needed --}}
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">
                        {{ isset($service) ? translate('Edit Service') : translate('Add New Service') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($service))
                            {{-- Laravel expects POST with _method PATCH/PUT for resource update --}}
                            @method('POST')
                        @endif

                        <div class="form-group mb-3">
                            <label for="name"><b>{{ translate('Name') }}</b></label>
                            <input
                                type="text"
                                placeholder="{{ translate('Name') }}"
                                id="name"
                                name="name"
                                class="form-control"
                                value="{{ old('name', isset($service) ? $service->name : '') }}"
                                required
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label for="type"><b>{{ translate('Type') }}</b></label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="fixed" {{ old('type', isset($service) ? $service->type : '') == 'fixed' ? 'selected' : '' }}>{{ translate('Fixed') }}</option>
                                <option value="percent" {{ old('type', isset($service) ? $service->type : '') == 'percent' ? 'selected' : '' }}>{{ translate('Percent') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price"><b>{{ translate('Price') }}</b></label>
                            <input
                                type="number" step="0.01" min="0"
                                id="price" name="price" class="form-control"
                                value="{{ old('price', isset($service) ? $service->price : '') }}"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description"><b>{{ translate('Description') }}</b></label>
                            <textarea name="description" id="description" class="form-control">{{ old('description', isset($service) ? $service->description : '') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label><b>{{ translate('Categories') }}</b></label>
                            @php
                                $selectedCats = old('categories', isset($service) ? $service->categories->pluck('id')->toArray() : []);
                            @endphp
                            @foreach($categories as $category)
                                <div class="d-flex align-items-center mb-2">
                                    <input
                                        type="checkbox" id="cat_{{ $category->id }}"
                                        name="categories[]" value="{{ $category->id }}"
                                        class="mr-2"
                                        {{ in_array($category->id, $selectedCats) ? 'checked' : '' }}
                                    />
                                    <label for="cat_{{ $category->id }}" class="mb-0">{{ $category->getTranslation('name') }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group mb-3">
                            <label for="sort_order"><b>{{ translate('Sort Order') }}</b></label>
                            <input
                                type="number" id="sort_order" name="sort_order"
                                class="form-control"
                                value="{{ old('sort_order', isset($service) ? $service->sort_order : 0) }}" min="0">
                        </div>

                        <div class="form-group mb-3">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="status" value="1"
                                    {{ old('status', isset($service) ? $service->status : 1) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span class="ml-2">{{ translate('Enable Service') }}</span>
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
        function update_service_status(el, id) {
            var status = el.checked ? 1 : 0;
            $.post('{{ url('admin/services') }}/' + id + '/update', {
                _token: '{{ csrf_token() }}',
                status: status
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Service status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
