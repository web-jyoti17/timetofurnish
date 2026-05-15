@extends('frontend.layouts.app')

@section('content')
<section class="pt-4 mb-4 ">
    <div class="container text-center  ">
        <div class="row ">
            <div class="col-lg-6 text-center text-lg-left">
                {{-- <h1 class="fw-700 fs-24 text-dark">{{ translate('Register your shop')}}</h1> --}}
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                        <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        "{{ translate('Register your shop')}}"
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="pt-4 mb-4 shopform">
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-xl-6 col-md-8 mx-auto formshadow">
               {{-- <h1 class="fw-700 fs-20 fs-md-24 text-dark text-center mb-3">{{ translate('Register Your Shop')}}</h1>--}}
                <form id="shop" class="" action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white border mb-4">
                        
                        <div class="fs-15 fw-600 p-3">
                            <h1 class="fw-700 fs-20 fs-md-24 text-dark text-center mb-3">{{ translate('Register Your Shop')}}</h1>
                        </div>
                        
                        <div class="fs-15 fw-600 p-3">
                            {{ translate('Personal Info')}}
                        </div>
                        <div class="p-3">
                            <div class="form-group">
                                <label>{{ translate(' Name')}} <span class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('Name') }}" name="name" required>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ translate(' Email')}} <span class="text-primary">*</span></label>
                                <input type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                           <div class="form-group">
                               
                               
                        {{--  <label>{{ translate(' Mobile Number') }} <span class="text-primary">*</span></label>

    <input
        type="tel"
        name="phone"
        class="form-control rounded-0{{ $errors->has('') ? ' is-invalid' : '' }}"
        value="{{ old('') }}"
        placeholder="{{ translate('') }}"
        required
        inputmode="numeric"
    
        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
    >

    @if ($errors->has('phone'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('Mobile Number') }}</strong>
        </span>
    @endif
</div>    --}}     
                               
                               
                               
    <label>{{ translate('Landline Number') }} <span class="text-primary"></span></label>

    <input type="text" class="form-control mb-3 rounded-0" name="landline_no"  inputmode="numeric"
                                  maxlength="14"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"  placeholder="{{ translate ('Landline Number') }}" value="" >

   @if ($errors->has('landline_no'))--}}
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('Landline  Number') }}</strong>
        </span>
   
@endif
<br>
                            <div class="form-group">
                                <label>{{ translate(' Password')}} <span class="text-primary">*</span></label>
                                <input type="password" class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}" placeholder="{{  translate('Password') }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Repeat Password')}} <span class="text-primary">*</span></label>
                                <input type="password" class="form-control rounded-0" placeholder="{{  translate('Confirm Password') }}" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border mb-4">
                        <div class="fs-15 fw-600 p-3">
                            {{ translate('Basic Info')}}
                        </div>
                        <div class="p-3">
                            <div class="form-group">
                                <label>{{ translate('Shop/Business Name')}} <span class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0{{ $errors->has('shop_name') ? ' is-invalid' : '' }}" value="{{ old('shop_name') }}" placeholder="{{ translate('Shop Name')}}" name="shop_name" required>
                                @if ($errors->has('shop_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('shop_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Address')}} <span class="text-primary">*</span></label>
                                <input type="text" class="form-control mb-3 rounded-0{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') }}" placeholder="{{ translate('Address')}}" name="address" required>
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group">
                               
                               
                                     <label>{{ translate(' Mobile Number') }} <span class="text-primary">*</span></label>
                                 <input type="text" class="form-control mb-3 rounded-0"  name="phone"  inputmode="numeric" placeholder="{{translate ('Mobile number')}}" maxlength="14"   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="" requried>
                                      @if ($errors->has('phone'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('Mobile Number') }}</strong>
                                                    </span>
                                        @endif
                           </div>         
                           
                            
                            
                            
                            <div class="form-group">
                                <label>{{ translate('Country')}} <span class="text-primary">*</span></label>
                                <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country')}}" name="country_id" id="edit_country" required>
                                    <option value="">{{ translate('Select your country') }}</option>
                                    @foreach (get_active_countries() as $key => $country)
                                    <option @if($country->id == 230) selected @endif value="{{ $country->id }}">
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            {{--<div class="form-group">
                                <label>{{ translate('State')}} <span class="text-primary">*</span></label>
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>

                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('City')}} <span class="text-primary">*</span></label>
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true"  name="city_id" required>

                                </select>
                            </div>--}}
                            
                            <div class="form-group">
                                <label>{{ translate('City')}} <span class="text-primary">*</span></label>
                                <input class="form-control mb-3 aiz-selectpicker rounded-0" type="text" name="city_id" placeholder="{{ translate(' City')}}" value="{{old('city_id')}}" required/> 
                            </div>
                            
                            <div class="form-group">
                                <label>{{ translate('Post Code')}} <span class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" value="{{ old('postal_code') }}" placeholder="{{ translate('Post Code')}}" name="postal_code" required>
                                @if ($errors->has('postal_code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('postal_code') }}</strong>
                                    </span>
                                @endif
                                
                                
<!--                            <div class="form-group mt-3">-->
<!--    <label class="aiz-checkbox">-->
<!--        <input type="checkbox" name="seller_policy" required>-->
<!--        <span>-->
<!--       I confirm that I have read and accept all customer terms and conditions.  -->
<!--            <a href='https://timetofurnish.com/terms' target="_blank" class="text-primary">-->
<!--    Seller Policy-->
<!--</a>-->

<!--        </span>-->
<!--        <span class="aiz-square-check"></span>-->
<!--    </label>-->
<!--</div>-->

<!--Terms and Conditions -->



                                             <br>   <div class="mb-3"> 
                                                        <input type="checkbox" name="checkbox_example_1" required> I have fully read and agree to abide by all <a href="javascript:void:0" id="openModalBtn" data-id="5"> <b>Terms and Conditions </b> </a> in the Seller Policy. 
. 
                                                        
                                                </div>

                    </div>
                    
 
 
                    <!-- Modal -->
                     

                    @if(get_setting('google_recaptcha') == 1)
                        <div class="form-group mt-2 mx-auto row">
                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                        </div>
                    @endif

                    <div class="text-right">
                        <button type="submit" class="btn borderbtn fw-600 rounded-0">{{ translate('Register Your Shop')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Modal Structure -->
<div id="myModal" class="modal">
  <div class="modal-content"   >
    <span  class="close">&times;</span>
<div >   
         @php
        $page = \App\Models\Page::where('id', 5)->first();
          @endphp
 


    <h2>{{ $page->title }}</h2>
      <p2>@php echo $page->content @endphp</p>
</div>
  </div>
</div>

@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
    // making the CAPTCHA  a required field for form submission
    $(document).ready(function(){
        $("#shop").on("submit", function(evt)
        {
            var response = grecaptcha.getResponse();
            if(response.length == 0)
            {
            //reCaptcha not verified
                alert("please verify you are human!");
                evt.preventDefault();
                return false;
            }
            //captcha verified
            //do the rest of your validations here
            $("#reg-form").submit();
        });
    });

    $(document).on('change', '[name=country_id]', function() {
        var country_id = $(this).val();
        get_states(country_id);
    });

    $(document).on('change', '[name=state_id]', function() {
        var state_id = $(this).val();
        get_city(state_id);
    });
    
    function get_states(country_id) {
        $('[name="state"]').html("");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route('get-state')}}",
            type: 'POST',
            data: {
                country_id  : country_id
            },
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj != '') {
                    $('[name="state_id"]').html(obj);
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            }
        });
    }

    function get_city(state_id) {
        $('[name="city"]').html("");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route('get-city')}}",
            type: 'POST',
            data: {
                state_id: state_id
            },
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj != '') {
                    $('[name="city_id"]').html(obj);
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            }
        });
    }


var modal = document.getElementById("myModal");
var openBtn = document.getElementById("openModalBtn");
var closeBtn = document.getElementsByClassName("close")[0];

openBtn.onclick = function() {
  modal.style.display = "block";
}

closeBtn.onclick = function() {
  modal.style.display = "none";
}

// Close modal by clicking outside
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>
<style>



.modal {
  display: none; /* Hidden by default */
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.6);
}

/* Modal Box */
.modal-content {
  background: #fff;
  height:400px;
  overflow:auto;
  width: 50%;
  margin: 10% auto;
  padding: 20px;
  border-radius: 10px;
  position: relative;
  animation: fadeIn 0.4s ease;
}
@media (max-width: 564px) {
.modal-content {

  height:600px;
  overflow:auto;
  width: 80%;


}
}


/* Close button */
.close {
  position: fixed;
  top: 10px;
  right: 15px;
  font-size: 30px;
  cursor: pointer;
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.8); }
  to   { opacity: 1; transform: scale(1); }
}

</style>
@endsection
