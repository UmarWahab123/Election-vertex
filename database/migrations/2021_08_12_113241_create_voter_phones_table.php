<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoterPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voter_phones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('polling_detail_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->foreign('polling_detail_id')->references('id')->on('polling_details')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('voter_phones');
    }
}
