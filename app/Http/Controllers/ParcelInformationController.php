<?php

namespace App\Http\Controllers;

use App\Models\ParcelInformation;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

use Log;

use App\Traits\PricingModelTrait;

class ParcelInformationController extends Controller
{
    use PricingModelTrait;

    // GET /api/parcels | returns all parcels
    public function getAllParcelInformation()
    {
      $this->calculatePricingModel();

      $parcels = ParcelInformation::with('pricing_model')->get();

      // If no parcels found return message
      if ($parcels !== null) {
        return response()->json(['success' => true, 'message' => null, 'data' => $parcels]);
      } else {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No parcels found in system'], 422);
      }
    }

    // GET /api/parcels/{id} | returns a parcel
    public function getParcelInformation($id)
    {
      $parcel = ParcelInformation::with('pricing_model')->where('id', $id)->first();

      // If no parcel found return message
      if ($parcel != null) {
        return response()->json(['success' => true, 'message' => null, 'data' => $parcel]);
      } else {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No parcel with that ID found'], 422);
      }
    }

    // DELETE /api/parcels/{id} | deletes a parcel
    public function deleteParcelInformation($id)
    {
      $parcel = ParcelInformation::where('id', $id)->first();

      // If no parcel found return message
      if ($parcel != null) {
        // If parcel found delete and return success true
        $parcel->delete();
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No parcel with that ID found'], 422);
      }
    }

    // POST /api/parcels | Add a new parcel
    public function addParcelInformation(Request $request)
    {
      // check if request is empty
      if ($request->all() == null) {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No information passed along'], 422);
      }

      // Try validating request against information in model this is used for the trait
      try {
        $calculate_validated_data = $this->validate($request, ParcelInformation::$calculateRules);
      } catch (ValidationException $ex) {
        return response()->json(['success' => false, 'data' => null, 'message' => $ex->errors()], 422);
      }

      $pricing_model = $this->calculatePricingModel($calculate_validated_data->weight, $calculate_validated_data->volume, $calculate_validated_data->declared_value);

      // Check if pricing model has return false
      if (!$pricing_model) {
        return response()->json(['success' => false, 'data' => null, 'message' => 'Optimal pricing modal could not be found'], 422);
      }

      // Add those calculated values to the request for validation
      $request->request->add([
        'pricing_model_id' => $pricing_model['pricing_model_id'],
        'quote' => $pricing_model['quote']
      ]);

      // Try validating request against information in model
      try {
        $validated_data = $this->validate($request, ParcelInformation::$createRules);
      } catch (ValidationException $ex) {
        return response()->json(['success' => false, 'data' => null, 'message' => $ex->errors()], 422);
      }

      // Create new parcel using the validated data
      try {
        $parcel = ParcelInformation::create($validated_data);
      } catch (QueryException $ex) {
        return response()->json(['success' => false, 'data' => null, 'message' => $ex], 422);
      }

      // If no parcel found return message
      if ($parcel != null) {
        return response()->json(['success' => true, 'message' => null, 'data' => $parcel]);
      } else {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No parcel with that ID found'], 422);
      }
    }

    // PATCH /api/parcels/{id} | Update a parcel
    public function updateParcelInformation(Request $request, $id)
    {
      // check if request is empty
      if ($request->all() == null || $id == null) {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No information passed along'], 422);
      }

      // Try validating request against information in model this is used for the trait
      try {
        $calculate_validated_data = $this->validate($request, ParcelInformation::$calculateRules);
      } catch (ValidationException $ex) {
        return response()->json(['success' => false, 'data' => null, 'message' => $ex->errors()], 422);
      }

      $pricing_model = $this->calculatePricingModel($calculate_validated_data->weight, $calculate_validated_data->volume, $calculate_validated_data->declared_value);

      // Check if pricing model has return false
      if (!$pricing_model) {
        return response()->json(['success' => false, 'data' => null, 'message' => 'Optimal pricing modal could not be found'], 422);
      }

      // Add those calculated values to the request for validation
      $request->request->add([
        'pricing_model_id' => $pricing_model['pricing_model_id'],
        'quote' => $pricing_model['quote']
      ]);

      // Try validating request against information in model
      try {
        $validated_data = $this->validate($request, ParcelInformation::$updateRules);
      } catch (ValidationException $ex) {
        return response()->json(['success' => false, 'data' => null, 'message' => $ex->errors()], 422);
      }

      // Update parcel using the validated data
      try {
        $parcel = ParcelInformation::where('id', $id)->update($validated_data);
      } catch (QueryException $ex) {
        return response()->json(['success' => false, 'data' => null, 'message' => $ex], 422);
      }

      // If no parcel found return message
      if ($parcel != null) {
        return response()->json(['success' => true, 'message' => null, 'data' => $parcel]);
      } else {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No parcel with that ID found'], 422);
      }
    }

    // GET /api/prices/{parcelIds?} | returns all parcel pricings
    public function getParcelPricing(Request $request)
    {
      // Check if parcel ids passed along
      if ($request->parcelIds == null) {
        return response()->json(['success' => false, 'data' => null, 'message' => 'parcelIds not passed'], 422);
      }

      // Create array from the IDs
      $parcel_ids = explode(',', $request->parcelIds);

      // Get the parcels
      $parcel_prices = ParcelInformation::whereIn('id', $parcel_ids)->get();

      // Check if parcels found
      if ($parcel_prices == null) {
        return response()->json(['success' => false, 'data' => null, 'message' => 'No Parcels found'], 422);
      }

      // Create an array to return information containing formatted quote with id and item
      $formatted_parcel_prices = array();
      foreach ($parcel_prices as $parcel_price) {
        $formatted_parcel_prices[] = [
          'id' => $parcel_price->id,
          'item' => $parcel_price->item,
          'quote' => $parcel_price->formatted_quote,
        ];
      }

      $data = array(
        'total' => '$'.($parcel_prices->sum('quote') / 100),
        'parcel_prices' => $formatted_parcel_prices
      );

      return response()->json(['success' => true, 'message' => null, 'data' => $data]);
    }
}
