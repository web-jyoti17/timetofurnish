@extends('frontend.layouts.app')

@section('content')
<!-- Breadcrumb Section -->
<section class="pb-3 pt-4 title breadcrumb-banner">
    <div class="container text-center breadcrumbfont">
        <div class="row">
            <div class="col-lg-12">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center">
                    <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                        <a class="text-reset" href="{{ route('home') }}">
                            {{ translate('Home') }}
                        </a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        "{{ translate('Career') }}"
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Existing Career Form Section -->
<section class="gry-bg py-6" style="background:#FAF7F2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="career-card p-4 p-lg-5 h-100">

                    <div class="text-center mb-4">
                        <h1 class="fs-22 fw-600 text-primary">Be Part of Our Team</h1>
                        <h5 class="fs-14 fw-400 text-dark">Apply Now</h5>
                    </div>

                @if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
                    <form action="{{ route('career.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                       <div class="form-group mb-3">
                            <label class="fs-12 fw-700 text-soft-dark">Full Name</label>
                            <input type="text" name="name" required
                                   class="form-control rounded-0"
                                   placeholder="Your Name"
                                   maxlength="30"
                                   onkeypress="return /[a-zA-Z]/i.test(event.key)">
                        </div>
                         <div class="form-group mb-3">
                            <label class="fs-12 fw-700 text-soft-dark">Email Address</label>
                            <input type="email" name="email" required
                                   class="form-control rounded-0"
                                   placeholder="example@mail.com">
                        </div>

                        <div class="form-group mb-3">
                               <label class="fs-12 fw-700 text-soft-dark">Phone Number</label>
                                <input type="tel" name="phone" required
                                       class="form-control rounded-0"
                                       placeholder="Phone Number"
                                       pattern="[0-9]{1,14}"
                                       maxlength="14"
                                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                      </div>

                      <div class="form-group mb-3">
    <label class="fs-12 fw-700 text-soft-dark">Role Looking For</label>
    <input type="text" name="role" required
           class="form-control rounded-0"
           placeholder="Role You Are Applying For"
           oninput="limitWords(this,20)">
</div>
              {{--  <div class="form-group mb-3">
                    <label class="fs-12 fw-700 text-soft-dark">Message</label>
                    <textarea name="message" rows="4"
                        class="form-control rounded-0"
                        placeholder="Write your message here..."></textarea>
                </div>--}}
                                        <div class="form-group mb-3">
                            <label class="fs-12 fw-700 text-soft-dark">Upload CV</label>
                            <input type="file" name="cv" accept=".pdf,.doc,.docx" required
                                   class="form-control rounded-0">
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-block fw-700 fs-14 rounded-4">
                                Submit Application
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<style>
.career-card {
    background: #dacbbc;
    border-radius: 0px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    position: relative;
    padding: 2rem;
}

/* Common input underline style */
.form-group input.form-control {
    border: none !important;
    border-bottom: 1.5px solid #dcdcdc !important;
    border-radius: 0 !important;
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

/* Labels */
.form-group label {
    margin-bottom: 4px;
    font-size: 12px;
    color: #777;
}

.btn {
    color: white;
    background: #685b4e;
}
.btn:hover {
    color: white !important;
    text-decoration: none;
}
</style>

@endsection
<script>
    function limitWords(field, maxWords) {
    let words = field.value.split(/\s+/);
    if (words.length > maxWords) {
        field.value = words.slice(0, maxWords).join(" ");
    }
}
</script>