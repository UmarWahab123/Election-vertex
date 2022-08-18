<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FirebaseUrls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firebase_urls', function (Blueprint $table) {
            $table->integer('id');
            $table->text('image_url')->nullable();
            $table->integer('status')->default(0);
            $table->integer('url_upload_log_id')->nullable();
            $table->integer('cron')->default(0);
            $table->double('import_ps_number')->nullable();
            $table->text('log_state')->nullable();
            $table->text('link_meta')->nullable();
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
        Schema::dropIfExists('firebase_urls');

    }
}
