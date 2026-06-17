@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Add Team Member') }}</h1>
        </div>
    </div>
</div>
<div class="card">
    <form action="{{ route('team-members.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{ translate('Name') }} <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="{{ translate('Name') }}" name="name" value="{{ old('name') }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{ translate('Email') }}</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" placeholder="{{ translate('Email') }}" name="email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{ translate('Role / Info') }}</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="5" name="bio" placeholder="{{ translate('Info about this team member') }}">{{ old('bio') }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{ translate('Profile Photo') }}</label>
                <div class="col-sm-10">
                    <input type="file" name="photo" class="form-control">
                    <small class="text-muted">{{ translate('Optional. JPG, PNG, WEBP.') }}</small>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{ translate('Active') }}</label>
                <div class="col-sm-10">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" name="is_active" checked>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('team-members.index') }}" class="btn" style="background:#39322a;color:#dacbbc;border:1px solid #39322a;margin-right:8px;">{{ translate('Back') }}</a>
            <button type="submit" class="btn" style="background:#685b4e;color:#ffffff;border:1px solid #685b4e;">{{ translate('Save Member') }}</button>
        </div>
    </form>
</div>
@endsection
