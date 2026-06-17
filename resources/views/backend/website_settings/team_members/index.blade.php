@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Team Members') }}</h1>
        </div>
        <div class="col text-end">
            @can('add_website_page')
            <a href="{{ route('team-members.create') }}" class="btn" style="background:#685b4e;color:#ffffff;border-radius:24px;padding:10px 22px;border:1px solid #685b4e;">{{ translate('Add Team Member') }}</a>
            @endcan
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <style>
            .theme-switch .form-check-input{
                -webkit-appearance:none;appearance:none; width:46px;height:26px;background:#e9e7e4;border-radius:999px;position:relative;outline:none;cursor:pointer;transition:background .2s;border:none;
            }
            .team_badge{display:inline-block;margin-left:12px;padding:4px 10px;font-size:12px;border-radius:12px;}
            .theme-switch .form-check-input::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;box-shadow:0 1px 2px rgba(0,0,0,0.2);transition:transform .2s;}
            .theme-switch .form-check-input:checked{background:#685b4e;}
            .theme-switch .form-check-input:checked::after{transform:translateX(20px);}
            .form-check.form-switch{display:inline-flex;align-items:center;}
            .theme-add-btn{background:#685b4e;color:#fff;border-radius:30px;padding:10px 22px;border:1px solid #685b4e;}
        </style>
        <form action="{{ route('team-members.update-status') }}" method="POST" class="row g-3 align-items-center">
            @csrf
            <div class="col-3">
                <h6 class="mb-0">{{ translate('Team page status') }}</h6>
            </div>
            <div class="col-3">
                <input type="hidden" name="status" value="0">
                <div class="form-check form-switch theme-switch">
                    <input class="form-check-input" type="checkbox" id="teamPageStatus" name="status" value="1" {{ $team_page_status == 1 ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label ms-2" for="teamPageStatus" style="color:#39322a;">{{ $team_page_status == 1 ? translate('Enabled') : translate('Disabled') }}</label>
                
                 <span class="team_badge" style="background: #dacbbc; color: #39322a;">{{ $team_page_status == 1 ? translate('Visible on frontend') : translate('Hidden from frontend') }}</span>
            </div>
            </div>
         
        </form>
    </div>
</div>

<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header" style="background: #39322a; border-bottom: 1px solid #685b4e;">
        <h6 class="mb-0 fw-600" style="color: #dacbbc;">{{ translate('Team page banner and card settings') }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('team-members.update-settings') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row gy-3">
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Banner Title') }}</label>
                    <input type="text" name="banner_title" value="{{ old('banner_title', get_setting('team_members_banner_title')) }}" class="form-control" placeholder="{{ translate('Enter banner title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Banner Subtitle') }}</label>
                    <input type="text" name="banner_subtitle" value="{{ old('banner_subtitle', get_setting('team_members_banner_subtitle')) }}" class="form-control" placeholder="{{ translate('Enter banner subtitle') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-600">{{ translate('Banner Description') }}</label>
                    <textarea name="banner_description" class="form-control" rows="4" placeholder="{{ translate('Enter banner description') }}">{{ old('banner_description', get_setting('team_members_banner_description')) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Banner Image') }}</label>
                    <input type="file" name="banner_image" class="form-control" style="border-color: #685b4e;">
                    @if(get_setting('team_members_banner_image'))
                        <div class="mt-3 position-relative d-inline-block">
                            <img src="{{ asset(get_setting('team_members_banner_image')) }}" class="img-fluid rounded" style="max-height: 120px; width: auto;" alt="Banner Image">
                            <button type="button" class="btn btn-sm position-absolute top-0 end-0" style="background: rgba(57,50,42,0.95); color: #dacbbc; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;" onclick="document.getElementById('remove_banner_image').value='1'; this.closest('.position-relative').style.display='none';">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                    @endif
                    <input type="hidden" name="remove_banner_image" id="remove_banner_image" value="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Default card image') }}</label>
                    <input type="file" name="card_image" class="form-control" style="border-color: #685b4e;">
                    @if(get_setting('team_members_card_image'))
                        <div class="mt-3 position-relative d-inline-block">
                            <img src="{{ asset(get_setting('team_members_card_image')) }}" class="img-fluid rounded" style="max-height: 120px; width: auto;" alt="Card Image">
                            <button type="button" class="btn btn-sm position-absolute top-0 end-0" style="background: rgba(57,50,42,0.95); color: #dacbbc; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;" onclick="document.getElementById('remove_card_image').value='1'; this.closest('.position-relative').style.display='none';">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                    @endif
                    <input type="hidden" name="remove_card_image" id="remove_card_image" value="0">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn" style="background: #685b4e; color: #ffffff; border: 1px solid #685b4e;">{{ translate('Save Team Page Settings') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-600">{{ translate('Team Member List') }}</h6>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Name') }}</th>
                    <th>{{ translate('Email') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team_members as $key => $member)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>
                        @if($member->is_active)
                            <span class="badge badge-inline badge-success">{{ translate('Active') }}</span>
                        @else
                            <span class="badge badge-inline badge-secondary">{{ translate('Inactive') }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @can('edit_website_page')
                        <a href="{{ route('team-members.edit', $member->id) }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Edit') }}">
                            <i class="las la-pen"></i>
                        </a>
                        @endcan
                        @can('delete_website_page')
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('team-members.destroy', $member->id) }}" title="{{ translate('Delete') }}">
                            <i class="las la-trash"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
