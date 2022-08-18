<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PageSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_setting', function (Blueprint $table) {
            $table->id();
            $table->string('business_id')->nullable();
            $table->string('tag_name')->nullable();
            $table->string('businessHome_H1')->nullable();
            $table->string('businessHome_H2')->nullable();
            $table->string('businessHome_H3')->nullable();
            $table->string('reg_title')->nullable();
            $table->string('reg_img_title')->nullable();
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
        Schema::dropIfExists('page_setting');

    }
}
