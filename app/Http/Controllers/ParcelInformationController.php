<?php

namespace App\Http\Controllers;

use App\Models\ParcelInformation;

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
}
