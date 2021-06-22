<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('parcel_information', function (Blueprint $table) {
        $table->increments('id');
        $table->string('item');
        $table->integer('weight')->nullable();
        $table->integer('volume')->nullable();
        $table->integer('declared_value')->nullable();
        $table->integer('pricing_model_id');
        $table->integer('quote');
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('parcel_information');
    }
}
