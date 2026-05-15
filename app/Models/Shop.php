<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{

  protected $with = ['user'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function country()
  {
    return $this->belongsTo(Country::class);
  }

  /*public function state()
  {
    return $this->belongsTo(State::class);
  }
  */
  public function city()
  {
    return $this->belongsTo(City::class);
  }
  
  public function seller_package(){
    return $this->belongsTo(SellerPackage::class);
  }
  public function followers(){
    return $this->hasMany(FollowSeller::class);
  }
}
