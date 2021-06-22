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

    // Validation rules to create new entry
    public static $calculateRules = [
     'weight'=>'required_without:volume,declared_value|integer',
     'volume'=>'required_without:weight,declared_value|integer',
     'declared_value'=>'required_without:volume,weight|integer',
    ];

    // Validation rules to create new entry
    public static $createRules = [
     'item'=>'required|max:255',
     'weight'=>'required_without:volume,declared_value|integer',
     'volume'=>'required_without:weight,declared_value|integer',
     'declared_value'=>'required_without:volume,weight|integer',
     'pricing_model_id'=>'required|integer|exists:pricing_models,id',
     'quote'=>'required|integer',
    ];

    // Validation rules to updating an entry
    public static $updateRules = [
     'item'=>'required|max:255',
     'weight'=>'required_without:volume,declared_value|integer',
     'volume'=>'required_without:weight,declared_value|integer',
     'declared_value'=>'required_without:volume,weight|integer',
     'pricing_model_id'=>'required|integer|exists:pricing_models,id',
     'quote'=>'required|integer',
    ];
}
