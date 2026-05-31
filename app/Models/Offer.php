<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Offer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'show_on_home' => 'integer',
        'priority' => 'integer',
        'discount_value' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product', 'offer_id', 'product_id');
    }

    public function scopeActive($query)
    {
        $now = Carbon::now();
        // Allow a timezone offset buffer of up to 14 hours for starts_at.
        // This ensures offers scheduled locally become active immediately on UTC servers.
        $startsNow = Carbon::now()->addHours(14); 

        return $query->where('status', 'approved')
                     ->where(function ($q) use ($startsNow) {
                          $q->whereNull('starts_at')
                            ->orWhere('starts_at', '<=', $startsNow);
                      })
                     ->where(function ($q) use ($now) {
                          $q->whereNull('ends_at')
                            ->orWhere('ends_at', '>=', $now);
                      });
    }

    public function scopeHomeSection($query)
    {
        return $query->active()->where('show_on_home', 1)->orderBy('priority', 'desc')->orderBy('id', 'desc');
    }

    public static function getBusyProductIds($excludeOfferId = null)
    {
        $now = Carbon::now();
        $query = self::whereIn('status', ['approved', 'pending'])
                     ->where(function ($q) use ($now) {
                         $q->whereNull('ends_at')
                           ->orWhere('ends_at', '>=', $now);
                     });

        if ($excludeOfferId) {
            $query->where('id', '!=', $excludeOfferId);
        }

        return $query->join('offer_product', 'offers.id', '=', 'offer_product.offer_id')
                     ->pluck('offer_product.product_id')
                     ->unique()
                     ->toArray();
    }
}

