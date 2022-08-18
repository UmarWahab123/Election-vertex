<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BalanceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_account', function (Blueprint $table) {
            $table->id();
            $table->string('business_id',20)->nullable();
            $table->string('ref_id',50)->nullable();
            $table->string('credit',50)->nullable();
            $table->text('details')->nullable();
            $table->string('debit',50)->nullable();
            $table->string('balance',50)->nullable();
            $table->text('img_url')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('status',11)->default('ACTIVE');
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
        Schema::dropIfExists('business_account');
    }
}
