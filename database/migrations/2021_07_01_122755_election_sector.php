<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ElectionSector extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('election_sector', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();

            $table->string('sector')->nullable();
            $table->string('block_code')->nullable();
            $table->string('male_vote')->nullable();
            $table->string('female_vote')->nullable();
            $table->string('total_vote')->nullable();
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
        //
    }
}
