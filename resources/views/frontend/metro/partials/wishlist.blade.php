<a href="{{ route('wishlists.index') }}" class="d-flex align-items-center text-dark" data-toggle="tooltip" data-title="{{ translate('Wishlist') }}" data-placement="top">
    <span class="position-relative d-inline-block">
    <svg xmlns="http://www.w3.org/2000/svg" class="svg-heart" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#685b4e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
    </svg>
        @if(Auth::check() && count(Auth::user()->wishlists)>0)
            <span class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count(Auth::user()->wishlists)}}</span>
        @endif
    </span>
</a>
