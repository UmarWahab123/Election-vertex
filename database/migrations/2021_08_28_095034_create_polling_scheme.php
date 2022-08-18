<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollingScheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polling_scheme', function (Blueprint $table) {
            $table->id();
            $table->integer('serial_no')->nullable();
            $table->string('ward')->nullable();
            $table->text('polling_station_area')->nullable();
            $table->text('polling_station_area_urdu')->nullable();
            $table->string('gender_type')->nullable();
            $table->text('block_code_area')->nullable();
            $table->integer('block_code')->nullable();
            $table->string('latlng')->nullable();
            $table->string('male_both')->nullable();
            $table->string('female_both')->nullable();
            $table->string('total_both')->nullable();
            $table->string('status')->Default('Pending');
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
        Schema::dropIfExists('polling_scheme');
    }
}
