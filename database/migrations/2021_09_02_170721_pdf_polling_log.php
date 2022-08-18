<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PdfPollingLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_polling_log', function (Blueprint $table) {
            $table->id();
            $table->text('key')->nullable();
            $table->text('value')->nullable();
            $table->text('meta')->nullable();
            $table->text('log')->nullable();
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
        Schema::dropIfExists('pdf_polling_log');
    }
}
