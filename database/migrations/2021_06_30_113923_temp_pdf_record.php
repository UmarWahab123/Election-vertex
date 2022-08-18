<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TempPdfRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_pdf_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pdf_poll_id')->nullable();
            $table->string('block_code')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('cnic')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('temp_pdf_record');

    }
}
