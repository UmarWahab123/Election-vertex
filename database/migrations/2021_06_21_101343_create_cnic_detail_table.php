<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnicDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnic_detail', function (Blueprint $table) {
            $table->id();
            $table->string('cnic_number')->nullable();
            $table->string('family_number')->nullable();
            $table->string('name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('address')->nullable();
            $table->string('polling_station')->nullable();
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
        Schema::dropIfExists('cnic_detail');
    }
}
