<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUcSectorToElectionSectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('election_sector', function (Blueprint $table) {
            $table->string('na_sector')->nullable();
            $table->string('uc_sector')->nullable();
            $table->string('pp_sector')->nullable();
            $table->text('response')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
