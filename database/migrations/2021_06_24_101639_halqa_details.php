<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HalqaDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polling_details', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('polling_station_id')->nullable();
            $table->integer('polling_station_number')->nullable();
            $table->string('cnic')->nullable();
            $table->string('gender')->nullable();
            $table->string('age')->nullable();
            $table->string('family_no')->nullable();
            $table->string('serial_no')->nullable();

            $table->string('page_no')->nullable();
            $table->text('url')->nullable();
            $table->integer('url_id')->nullable();
            $table->text('pic_slice')->nullable();
            $table->text('crop_settings')->nullable();
            $table->text('boundingBox')->nullable();
            $table->text('polygon')->nullable();
            $table->text('urdu_meta')->nullable();
            $table->text('urdu_text')->nullable();
            $table->string('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('address')->nullable();
            $table->text('type')->default('textract');

            $table->integer('status')->default(0);
            $table->unique(['cnic','polling_station_number']);
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
        Schema::dropIfExists('polling_details');

    }
}
