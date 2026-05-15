@extends('frontend.layouts.app')

@section('content')

<section class="gry-bg py-6" style="background:#FAF7F2">
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-xl-10 col-lg-11">

                <div class="row gx-0 align-items-stretch maindiv">

                    {{-- ================= CUSTOMER LOGIN ================= --}}
                    <div class="col-lg-6 col-md-12 mb-4 mb-lg-0 ">
                        <div class="login-card p-4 p-lg-5 h-100">

                            <div class="text-center mb-4">
                                <h1 class="fs-22 fw-600 text-primary">{{ translate('Buyer Login') }}</h1>
                                <h5 class="fs-14 fw-400 text-dark">{{ ('Login to your Account') }}</h5>
                            </div>

                            <form class="form-default" action="{{ route('login') }}" method="POST">
                                @csrf

                                {{-- EMAIL / PHONE OTP --}}
                             @if (addon_is_activated('otp_system'))
                                                    <div class="form-group phone-form-group mb-1">
                                                        <label for="phone" class="fs-12 fw-700 text-soft-dark">{{  translate('Phone') }}</label>
                                                        <input type="tel" id="phone-code" class=" input form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} rounded-0" value="{{ old('phone') }}"  placeholder="" name="phone" autocomplete="off">
                                                    </div>

                                                    <input type="hidden" name="country_code" value="">
                                                    
                                                    <div class="form-group email-form-group mb-1 d-none">
                                                        <label for="email" class="fs-12 fw-700 text-soft-dark label">{{  translate('Email') }}</label>
                                                        <input type="email" class="form-control rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('johndoe@example.com') }}" name="email" id="email" autocomplete="off">
                                                        @if ($errors->has('email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="form-group text-right">
                                                        <button class="btn btn-link p-0 text-primary" type="button" onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <label for="email" class="fs-12 fw-700 text-soft-dark">{{  translate('Email') }}</label>
                                                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} rounded-0" value="{{ old('email') }}" placeholder="{{  translate('johndoe@example.com') }}" name="email" id="email" autocomplete="off">
                                                        @if ($errors->has('email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                {{-- PASSWORD --}}
                               <div class="form-group">
                                    
                                                    <label for="password" class="fs-12 fw-700 text-soft-dark">{{  translate('Password') }}</label>
                                                    
                                                        {{--<i class="las la-lock input-icon"></i>--}}
                                              
                                                 <div class="input-group">
        <input type="password"
               class="form-control rounded-0 {{ $errors->has('password') ? ' is-invalid' : '' }}"
               placeholder="{{ translate('Password')}}"
               name="password"
               id="buyer_password">

        <div class="input-group-append"> 
                <i class="las la-eye"  onclick="togglePassword('buyer_password')"
                  style="cursor:pointer; line-height:40px;"></i>
            </span>
        </div>
    </div>
                                                    
                                                </div>

                                                <div class="row mb-2">
                                                    <!-- Remember Me -->
                                                    <div class="col-6">
                                                        <label class="aiz-checkbox">
                                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                          <span class="fs-12">{{ translate('Remember Me') }}</span>
                                                            <span class="aiz-square-check check"></span>
                                                            
                                                        </label>
                                                    </div>
                                                    <!-- Forgot password -->
                                                    <div class="col-6 text-right">
                                                        <a href="{{ route('password.request') }}" class="text-reset fs-12 fw-400 text-dark "><u>{{ translate('Forgot password?')}}</u></a>
                                                    </div>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="mb-4 mt-4">
                                                    <button type="submit" class="btn  btn-block fw-700 fs-14 rounded-4" style="">{{  translate('Login As Buyer') }}</button>
                                                </div>
                            </form>
                             <!-- DEMO MODE -->
                                            @if (env("DEMO_MODE") == "On")
                                                <div class="mb-4">
                                                    <table class="table table-bordered mb-0">
                                                        <tbody>
                                                            {{-- <tr>
                                                                <td>{{ translate('Seller Account')}}</td>
                                                                <td>
                                                                    <button class="btn btn-info btn-sm" onclick="autoFillSeller()">{{ translate('Copy credentials') }}</button>
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td>{{ translate('Customer Account')}}</td>
                                                                <td>
                                                                    <button class="btn btn-info btn-sm" onclick="autoFillCustomer()">{{ translate('Copy credentials') }}</button>
                                                                </td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td>{{ translate('Delivery Boy Account')}}</td>
                                                                <td>
                                                                    <button class="btn btn-info btn-sm" onclick="autoFillDeliveryBoy()">{{ translate('Copy credentials') }}</button>
                                                                </td>
                                                            </tr> --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif

                            {{-- SOCIAL LOGIN --}}
                            @if(get_setting('google_login') || get_setting('facebook_login') || get_setting('apple_login'))
                                <div class="text-center my-3 fs-12 text-gray">
                                    {{ translate('Or Login With') }}
                                </div>

                                <ul class="list-inline social colored text-center">
                                    @if(get_setting('facebook_login'))
                                        <li class="list-inline-item">
                                            <a href="{{ route('social.login',['provider'=>'facebook']) }}" class="facebook">
                                                <i class="lab la-facebook-f"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @if(get_setting('google_login'))
                                        <li class="list-inline-item">
                                            <a href="{{ route('social.login',['provider'=>'google']) }}" class="google">
                                                <i class="lab la-google"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @if(get_setting('apple_login'))
                                        <li class="list-inline-item">
                                            <a href="{{ route('social.login',['provider'=>'apple']) }}" class="apple">
                                                <i class="lab la-apple"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif

                            <div class="text-center mt-4">
                                <p class="fs-12  mb-1">{{  ("Don't have an account?")  }}</p>
                                <a href="{{ route('user.registration') }}" class="fw-700">
                                    {{ translate('Register Now') }}
                                </a>
                            </div>

                        </div>
                    </div>

                    {{-- ================= SELLER LOGIN ================= --}}
                    <div class="col-lg-6 col-md-12">
    <div class="seller-card p-4 p-lg-5 h-100 mt-2">

        {{-- HEADER --}}
        <div class="mb-4 text-center">
           <h1 class="fs-22 fw-600 text-primary">{{ translate('Seller Login') }}</h1>
            <h5 class="fs-14 fw-400 text-dark">
                {{ translate('Login to  Seller Account') }}
            </h5>
        </div>

        {{-- SELLER LOGIN FORM --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="form-group">
                <label class="fs-12 fw-700 text-soft-dark">
                    {{ translate('Email') }}
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control rounded-0 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="seller@example.com"
                       autocomplete="off">

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            {{-- PASSWORD WITH TOGGLE --}}
            <div class="form-group">
                <label class="fs-12 fw-700 text-soft-dark">
                    {{ translate('Password') }}
                </label>
                <div class="input-group">
                    <input type="password"
                           name="password"
                           id="seller_password"
                           class="form-control rounded-0 {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="{{ translate('Password') }}">
                       <div class="input-group-append"> 
             <i class="las la-eye"  onclick="togglePassword('seller_password')"  style="cursor:pointer; line-height:40px;"></i> 
            </span>
        </div>
                    
                </div>
            </div>

            {{-- REMEMBER & FORGOT --}}
            <div class="row mb-2">
                <div class="col-6">
                    <label class="aiz-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="fs-12">{{ translate('Remember Me') }}</span>
                        <span class="aiz-square-check"></span>
                    </label>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('password.request') }}" class="text-reset fs-12 fw-400 text-dark ">
                        <u>{{ translate('Forgot password?') }}</u>
                    </a>
                </div>
            </div>

            {{-- SUBMIT --}}
            <button type="submit"
                    class="btn  btn-block fw-700 fs-14 rounded-4 mt-4">
                {{ translate('Login as Seller') }}
            </button>
            
             <div class="text-center mt-4">
                                <p class="fs-12  mb-1">{{ ("Don't have an account?") }}</p>
                                <a href="{{ route('shops.create') }}" class="fw-700">
                                    {{ ('Become a Seller') }}
                                </a>
                            </div>
        </form>

        {{-- DEMO MODE --}}
        @if (env("DEMO_MODE") == "On")
            <div class="mt-4">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>seller@example.com</td>
                            <td>123456</td>
                            <td>
                                <button class="btn btn-info btn-xs" onclick="autoFillSeller()">
                                    {{ translate('Copy') }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>


                </div>
            </div>
        </div>
    </div>
</section>



<style>
.aiz-checkbox .aiz-square-check {
    border-color: #001 !important;   /* black border */
}
/* wrapper */
.input-with-icon {
    position: relative;
}

/* input padding so placeholder starts after icon */
.input-with-icon .form-control {
    padding-left: 34px;
}

/* lock icon inside input */
.input-left-icon {
    position: absolute;
    left: 8px;
    bottom: 10px;        /* underline input ke hisab se */
    font-size: 16px;
    color: #b5b5b5;
    pointer-events: none;
}

/* focus highlight */
.input-with-icon .form-control:focus ~ .input-left-icon {
    color: #7b2ff7;
}

.lable{
    background:
    #dacbbc; !important
}
    .login-card,
.seller-card {
    background: #dacbbc;
    border-radius: 0px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    position: relative;
}
.login-card{
    background:white; !important
}

/* Overlay effect */
.seller-card {
    transform: translateY(-12px);
}

/* Divider effect */
@media (min-width: 992px) {
    .login-card::after {
        content: "";
        position: absolute;
        top: 40px;
        right: -32px;
        width: 1px;
        height: calc(100% - 80px);
        background: #e6e1d8;
    }
}
.btn{
    color:white;
    background:#685b4e;
}
.btn:hover {
    color: white !important;
    text-decoration: none;
}
/* Common input underline style */
.form-group input.form-control {
    border: none !important;
    border-bottom: 1.5px solid #dcdcdc !important;
    border-radius: 0 !important;+-++
    padding-left: 0;
    padding-right: 0;
    background-color: transparent;
    box-shadow: none !important;
    font-size: 14px;
}

/* On focus – purple highlight */
.form-group input.form-control:focus {
    border-bottom: 2px solid #7b2ff7 !important;
    outline: none;
}

/* Placeholder color */
.form-group input.form-control::placeholder {
    color: #292933;
}
.form-group label {
    margin-bottom: 4px;
    font-size: 12px;
    color: #777;
}
@media (min-width: 992px) {
    .login-card::after {
        right: -1px;   /* instead of -32px */
        width: 1px;
    }
}
.maindiv .col-md-12, .maindiv .col-lg-6{
    padding-right: 0px;
     padding-left: 0px;
} 
.mt-2, .my-2 {
    margin-top: .8rem !important;
}
</style>

@endsection

@section('script')
    <script type="text/javascript">
        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }

        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
        
        function autoFillDeliveryBoy(){
            $('#email').val('deliveryboy@example.com');
            $('#password').val('123456');
        }
        
       function togglePassword(id) {
   // alert(ths.id);   // button/icon id
 
  //  $('#'+id).show();   // show input if needed

    const passwordInput = document.getElementById(id); 
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}
        
    </script>
@endsection
