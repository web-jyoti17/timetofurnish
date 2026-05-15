<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function acceptAll()
    {
        $preferences = [
            'necessary' => true,
            'analytics' => true,
            'marketing' => true,
        ];

        return redirect()->back()
            ->cookie('cookie_preferences', json_encode($preferences), 60 * 24 * 180); // 180 days
    }

    public function savePreferences(Request $request)
    {
        $preferences = [
            'necessary' => true, // always required
            'analytics' => $request->has('analytics'),
            'marketing' => $request->has('marketing'),
        ];

        return redirect()->back()
            ->cookie('cookie_preferences', json_encode($preferences), 60 * 24 * 180);
    }
}
