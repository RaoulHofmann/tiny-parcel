<?php

namespace App\Traits;

use App\Models\PricingModel;

use Log;
/*
* This trait is used to calculate the approriate pricing model based on information from DB
*/
trait PricingModelTrait {

    /* Used to calculate the optimal pricing model
    *
    * @param integer $weight
    *        integer $volumn
    *        integer $declared_value
    *
    * @return pricing_model.id
    */
    public function calculatePricingModel($weight = null, $volumn = null, $declared_value = null)
    {
      // Go through each of the values and convert values to apporitate unit
      $calculated_values = [];

      // Get the pricing models
      $pricing_models = PricingModel::get();

      if ($weight !== null) {
        // Convert grams to kg
        $weight = $weight / 1000;

        // Get the value from the collection
        $calc_value = $pricing_models->where('id', 1)->first()->value;

        // Id 1 is kilogram value could be more dynamic in future by using a value besides the id to determine which one
        $calculated_values[1] = ($weight * $calc_value);
      }

      if ($volumn !== null) {
        // Convert cc to m^3
        $volumn = $volumn / 1e+6;

        // Get the value from the collection
        $calc_value = $pricing_models->where('id', 2)->first()->value;

        // Id 2 is cubic meter value could be more dynamic in future by using a value besides the id to determine which one
        $calculated_values[2] = ($volumn * $calc_value);
      }

      if ($declared_value !== null) {
        // Convert cc to m^3
        $declared_value = $declared_value / 100;

        // Get the value from the collection
        $calc_value = $pricing_models->where('id', 3)->first()->value;

        // Id 3 is declared value could be more dynamic in future by using a value besides the id to determine which one
        $calculated_values[3] = ($declared_value * ($calc_value / 100));
      }

      // Check if anything in the array
      if (count($calculated_values) <= 0) {
        return false;
      }

      // Get id and max value in array
      $optimal_quote = max($calculated_values);
      $pricing_model_id = array_search($optimal_quote, $calculated_values);

      return array('pricing_model_id' => $pricing_model_id, 'quote' => $optimal_quote * 100);
    }
}
