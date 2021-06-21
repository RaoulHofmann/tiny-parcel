<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelInformation extends Model
{
    // Table within the DB
    protected $table = 'parcel_information';

    // The attributes that are mass assignable.
    protected $fillable = [
      'item',
      'weight',
      'volume',
      'declared_value',
      'pricing_model_id',
      'quote',
    ];

    public function pricing_model()
    {
      return $this->belongsTo(PricingModel::class);
    }

    public function getQuoteAttribute($quote)
    {
      if ($quote !== null) {
        // converts cents to dollars for better reading
        return '$'.$quote / 100;
      } else {
        return null;
      }
    }
}
