<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExcelPollingDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voter_details', function (Blueprint $table) {
            $table->id();
            $table->string('id_card')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('family_no')->nullable();
            $table->string('block_code')->nullable();
            $table->longText('age')->nullable();
            $table->longText('name')->nullable();
            $table->longText('father_name')->nullable();
            $table->longText('address')->nullable();
            $table->string('cron')->nullable();
            $table->string('status')->nullable();
            $table->longText('meta')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voter_details');

    }
}
