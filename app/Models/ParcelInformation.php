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

    protected $appends = ['formatted_quote'];

    public function pricing_model()
    {
      return $this->belongsTo(PricingModel::class);
    }

    public function getFormattedQuoteAttribute()
    {
      if ($this->quote !== null) {
        // converts cents to dollars for better reading
        return '$'.$this->quote / 100;
      } else {
        return null;
      }
    }
}
