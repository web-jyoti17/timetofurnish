@php
    $prefs = json_decode(Cookie::get('cookie_preferences') ?? '{}', true);
@endphp

@if (empty($prefs))
<style>
  /* --- COOKIE BANNER STATIC STYLE --- */
  #cookie-banner {
    position: fixed;
    bottom:0;
    width: 100%;
    background: #000;
    color: #fff;
    font-family: system-ui, sans-serif;
    padding: 1.25rem 1rem;
    border-top: 1px solid rgba(255,255,255,.1);
  }
  #cookie-banner .container {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  #cookie-banner h3 { margin: 0; font-size: 1rem; font-weight: 600; }
  #cookie-banner p { margin: 0; font-size: .9rem; line-height: 1.4; color: #e5e7eb; }
  #cookie-banner a { color: #60a5fa; text-decoration: underline; }
  #cookie-banner form { display: flex; flex-direction: column; gap: .5rem; }
  #cookie-banner label { font-size: .9rem; display: flex; align-items: center; gap: .4rem; }
  #cookie-banner input[type="checkbox"] {
    accent-color: #2563eb;
    width: 16px; height: 16px;
  }
  #cookie-banner .actions { margin-top: .8rem; display: flex; flex-wrap: wrap; gap: .5rem; }
  #cookie-banner .btn {
    border: none; border-radius: 4px;
    padding: .55rem 1rem; font-size: .9rem;
    cursor: pointer; transition: background .2s ease;
  }
  #cookie-banner .btn.save { background: #2563eb; color: #fff; }
  #cookie-banner .btn.save:hover { background: #1d4ed8; }
  #cookie-banner .btn.accept { background: #374151; color: #fff; }
  #cookie-banner .btn.accept:hover { background: #4b5563; }
  @media (min-width: 768px) {
    #cookie-banner .container { flex-direction: row; justify-content: space-between; align-items: center; }
    #cookie-banner form { flex-direction: row; align-items: center; gap: 1.25rem; }
  }
  #custom-cookies{ display: none; }
</style>
<script>
     function custom_cookie(){ 
        document.getElementById('custom-cookies').style.display = "block";
    } 
</script>
<div id="cookie-banner" style="z-index: 20000">
  <div class="container">
    <div class="text">  <span></span>
      <h3>We Use Cookies 🍪</h3>
      <p>
        We use necessary cookies to make our site work, plus analytics and marketing cookies
        to improve your experience. You can change your choices anytime.
        See our <a target="_blank" href="https://timetofurnish.com/cookie-policy">Cookie Policy</a> for more info.
      </p>
    </div>
</div>
<div class="container">
    <form method="POST" action="{{ route('cookies.save') }}">
      @csrf
      <div id="custom-cookies">
          <label><input type="checkbox" checked disabled> Necessary (always active)</label>
      <label><input type="checkbox" name="analytics"> Analytics</label>
      <label><input type="checkbox" name="marketing"> Marketing</label>
      <button type="submit" class="btn save">Save Preferences</button>
      </div> 
    </form>

    <div class="actions">
        <button style="color: #fff;" id="custom-cookie" onclick="custom_cookie()" class="btn custom">Customize</button>
        <a href="{{ route('cookies.acceptAll') }}" class="btn accept">Accept All</a>
      </div>
  </div>
</div>
@endif
