<?php

namespace App\Http\Controllers;

use App\Models\PricingModel;

use Log;

class PricingModelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // GET /api/pricing_models | returns all pricing models
    public function getPricingModels()
    {
      $pricing_model = PricingModel::get();

      return response()->json(['success' => true, 'data' => $pricing_model]);
    }

    // GET /api/pricing_models/parcels | returns all pricing models
    public function getPricingModelParcels()
    {
      $pricing_model = PricingModel::with('parcel_informations')->get();

      return response()->json(['success' => true, 'data' => $pricing_model]);
    }
}
