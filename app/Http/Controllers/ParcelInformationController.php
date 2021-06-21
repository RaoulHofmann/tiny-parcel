<?php

namespace App\Http\Controllers;

use App\Models\ParcelInformation;

use Illuminate\Http\Request;

use Log;

class ParcelInformationController extends Controller
{
    // GET /api/parcels | returns all pricing models
    public function getAllParcelInformation()
    {
      $parcels = ParcelInformation::with('pricing_model')->get();

      // If no parcels found return message
      if ($parcels !== null) {
        return response()->json(['success' => true, 'data' => $parcels]);
      } else {
        return response()->json(['success' => false, 'message' => 'No parcels found in system']);
      }
    }

    // GET /api/parcels/{id} | returns all pricing models
    public function getParcelInformation($id)
    {
      $parcel = ParcelInformation::with('pricing_model')->where('id', $id)->first();

      // If no parcel found return message
      if ($parcel !== null) {
        return response()->json(['success' => true, 'data' => $parcel]);
      } else {
        return response()->json(['success' => false, 'message' => 'No parcel with that ID found']);
      }
    }

    // DELETE /api/parcels/{id} | returns all pricing models
    public function deleteParcelInformation($id)
    {
      $parcel = ParcelInformation::where('id', $id)->first();

      // If no parcel found return message
      if ($parcel !== null) {
        // If parcel found delete and return success true
        $parcel->delete();
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'message' => 'No parcel with that ID found']);
      }
    }

    // GET /api/prices/{parcelIds?} | returns all pricing models
    public function getParcelPricing(Request $request)
    {
      // Check if parcel ids passed along
      if ($request->parcelIds == null) {
        return response()->json(['success' => false, 'message' => 'parcelIds not passed']);
      }

      // Create array from the IDs
      $parcel_ids = explode(',', $request->parcelIds);

      // Get the parcels
      $parcel_prices = ParcelInformation::whereIn('id', $parcel_ids)->get();

      // Check if parcels found
      if ($parcel_prices == null) {
        return response()->json(['success' => false, 'message' => 'Parcels found']);
      }

      // Create an array to return containing formatted quote with id and item
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

      return response()->json(['success' => true, 'data' => $data]);
    }
}
