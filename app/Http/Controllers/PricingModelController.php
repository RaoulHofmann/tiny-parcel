<?php

namespace App\Http\Controllers;

use App\Models\PricingModel;

class PricingModelController extends Controller
{
    // GET /api/pricing_models | returns all pricing models
    public function getPricingModels()
    {
      $pricing_model = PricingModel::get();

      // If nothing found return message
      if ($pricing_model !== null) {
        return response()->json(['success' => true, 'message' => null, 'data' => $pricing_model]);
      } else {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No pricing model found'], 422);
      }
    }

    // GET /api/pricing_models/parcels | returns all pricing models
    public function getPricingModelParcels()
    {
      $pricing_model = PricingModel::with('parcel_informations')->get();

      // If nothing found return message
      if ($pricing_model !== null) {
        return response()->json(['success' => true, 'message' => null, 'data' => $pricing_model]);
      } else {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No pricing model found'], 422);
      }
    }
}
