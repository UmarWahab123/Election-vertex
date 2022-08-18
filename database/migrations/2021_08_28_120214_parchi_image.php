<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParchiImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parchi_image', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->text('Party')->nullable();
            $table->text('image_url')->nullable();
            $table->text('candidate_name')->nullable();
            $table->text('block_code')->nullable();
            $table->text('candidate_image_url')->nullable();
            $table->string('status')->default('ACTIVE');
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
        Schema::dropIfExists('parchi_image');
    }
}
