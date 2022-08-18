<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitingCardImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visiting_card_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('visiting_card_id')->unsigned()->nullable();
            $table->string('image_link')->nullable();
            $table->foreign('visiting_card_id')->references('id')->on('visiting_cards')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('visiting_card_images');
    }
}
