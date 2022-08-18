<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PollingScheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('polling_scheme', function (Blueprint $table) {
            $table->id();
            $table->string('ward')->nullable();
            $table->text('polling-station-area')->nullable();
            $table->text('block-code-area')->nullable();
            $table->integer('block-code')->nullable();
            $table->string('latlng')->nullable();
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
        Schema::drop('polling_scheme');
    }
}
