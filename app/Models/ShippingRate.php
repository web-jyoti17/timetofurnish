<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ShippingRate extends Model

{

    protected $fillable = [

        'name',

        'description',

        'rate',

        'free_threshold',

    ];

}