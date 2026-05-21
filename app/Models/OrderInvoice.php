<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    protected $fillable = [
        'order_id',
        'copy_type',
        'invoice_number',
        'invoice_name',
        'file_path',
        'generated_at',
    ];

    protected $dates = [
        'generated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
