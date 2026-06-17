<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_website_pages'])->only(['index', 'updatePageStatus']);
        $this->middleware(['permission:add_website_page'])->only(['create', 'store']);
        $this->middleware(['permission:edit_website_page'])->only(['edit', 'update', 'updatePageStatus']);
        $this->middleware(['permission:delete_website_page'])->only('destroy');
    }

    public function index(Request $request)
    {
        $team_members = TeamMember::orderBy('created_at', 'desc')->get();
        $team_page_status = get_setting('team_members_page_status', 0);

        return view('backend.website_settings.team_members.index', compact('team_members', 'team_page_status'));
    }

    public function create()
    {
        return view('backend.website_settings.team_members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:team_members,email',
            'bio' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $team_member = new TeamMember();
        $team_member->name = $request->name;
        $team_member->email = $request->email;
        $team_member->bio = $request->bio;
        $team_member->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '-', $file->getClientOriginalName());
            if (! file_exists(public_path('uploads/team'))) {
                mkdir(public_path('uploads/team'), 0755, true);
            }
            $file->move(public_path('uploads/team'), $filename);
            $team_member->photo = 'uploads/team/' . $filename;
        }

        $team_member->save();

        flash(translate('Team member has been added successfully'))->success();
        return redirect()->route('team-members.index');
    }

    public function edit($id)
    {
        $team_member = TeamMember::findOrFail($id);
        return view('backend.website_settings.team_members.edit', compact('team_member'));
    }

    public function update(Request $request, $id)
    {
        $team_member = TeamMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:team_members,email,' . $team_member->id,
            'bio' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $team_member->name = $request->name;
        $team_member->email = $request->email;
        $team_member->bio = $request->bio;
        $team_member->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '-', $file->getClientOriginalName());
            if (! file_exists(public_path('uploads/team'))) {
                mkdir(public_path('uploads/team'), 0755, true);
            }
            $file->move(public_path('uploads/team'), $filename);
            $team_member->photo = 'uploads/team/' . $filename;
        }

        $team_member->save();

        flash(translate('Team member has been updated successfully'))->success();
        return redirect()->route('team-members.index');
    }

    public function destroy($id)
    {
        $team_member = TeamMember::findOrFail($id);
        $team_member->delete();

        flash(translate('Team member has been deleted successfully'))->success();
        return back();
    }

    public function updatePageStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        BusinessSetting::updateOrCreate([
            'type' => 'team_members_page_status'
        ], [
            'value' => $request->status
        ]);

        Cache::forget('business_settings');

        flash(translate('Team page visibility has been updated'))->success();
        return redirect()->route('team-members.index');
    }

    public function updatePageSettings(Request $request)
    {
        $request->validate([
            'banner_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string|max:1000',
            'banner_description' => 'nullable|string|max:2000',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'card_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $settings = [
            'team_members_banner_title' => $request->banner_title,
            'team_members_banner_subtitle' => $request->banner_subtitle,
            'team_members_banner_description' => $request->banner_description,
        ];

        foreach ($settings as $type => $value) {
            BusinessSetting::updateOrCreate([
                'type' => $type,
            ], [
                'value' => $value,
            ]);
        }

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '-', $file->getClientOriginalName());
            if (! file_exists(public_path('uploads/team'))) {
                mkdir(public_path('uploads/team'), 0755, true);
            }
            $file->move(public_path('uploads/team'), $filename);
            BusinessSetting::updateOrCreate([
                'type' => 'team_members_banner_image',
            ], [
                'value' => 'uploads/team/' . $filename,
            ]);
        } elseif ($request->has('remove_banner_image')) {
            $bannerImage = BusinessSetting::where('type', 'team_members_banner_image')->first();
            if ($bannerImage && $bannerImage->value && file_exists(public_path($bannerImage->value))) {
                unlink(public_path($bannerImage->value));
            }
            BusinessSetting::updateOrCreate([
                'type' => 'team_members_banner_image',
            ], [
                'value' => '',
            ]);
        }

        if ($request->hasFile('card_image')) {
            $file = $request->file('card_image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '-', $file->getClientOriginalName());
            if (! file_exists(public_path('uploads/team'))) {
                mkdir(public_path('uploads/team'), 0755, true);
            }
            $file->move(public_path('uploads/team'), $filename);
            BusinessSetting::updateOrCreate([
                'type' => 'team_members_card_image',
            ], [
                'value' => 'uploads/team/' . $filename,
            ]);
        } elseif ($request->has('remove_card_image')) {
            $cardImage = BusinessSetting::where('type', 'team_members_card_image')->first();
            if ($cardImage && $cardImage->value && file_exists(public_path($cardImage->value))) {
                unlink(public_path($cardImage->value));
            }
            BusinessSetting::updateOrCreate([
                'type' => 'team_members_card_image',
            ], [
                'value' => '',
            ]);
        }

        Cache::forget('business_settings');

        flash(translate('Team page settings updated successfully'))->success();
        return redirect()->route('team-members.index');
    }
}
