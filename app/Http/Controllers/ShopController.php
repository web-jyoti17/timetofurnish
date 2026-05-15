<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerRegistrationRequest;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\BusinessSetting;
use Auth;
use Hash;
use App\Models\City;
use App\Models\State;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        $states = State::where('status', 1)->where('country_id', $shop->country_id)->get();
        //$cities = City::where('status', 1)->where('state_id', $shop->state_id)->get();
        $cities = [];
        return view('seller.shop', compact('shop', 'states', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
                flash(translate('Admin or Customer cannot be a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'seller') {
                flash(translate('This user already a seller'))->error();
                return back();
            }
        } else {
            return view('frontend.seller_form');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerRegistrationRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            $shop->phone = $request->phone;
           $shop->landline_no = $request->landline_no; 
            $shop->country_id = $request->country_id;
            //$shop->state_id = $request->state_id;
            $shop->city_id = $request->city_id;
            $shop->postal_code = $request->postal_code;
       

            // 'seller_policy' => 'accepted';
            $shop->slug = preg_replace('/\s+/', '-', str_replace("/", " ", $request->shop_name));
            $shop->save();
            
             auth()->login($user, false);
             if (BusinessSetting::where('type', 'email_verification')->first()->value == 0) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
            } else {
                $user->notify(new EmailVerificationNotification());
            } 

            flash(translate('Your Shop has been created successfully,Verification mail has been Sent to your email.'))->success();
            return redirect()->route('seller.shop.index');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
