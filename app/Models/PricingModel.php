<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingModel extends Model
{
    // Table within the DB
    protected $table = 'pricing_models';

    // The attributes that are mass assignable.
    protected $fillable = [
      'name',
      'description',
      'value'
    ];

    public function parcel_informations()
    {
      return $this->hasMany(ParcelInformation::class);
    }
}
