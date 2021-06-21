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
      'volumn',
      'declared_value',
      'pricing_model_id',
      'quote',
    ];

    public function pricing_model()
    {
      return $this->belongsTo(PricingModel::class);
    }
}
