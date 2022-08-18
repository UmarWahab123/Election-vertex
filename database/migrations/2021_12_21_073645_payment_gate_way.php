<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaymentGateWay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway', function (Blueprint $table) {
            $table->id();
            $table->string('business_id',20)->nullable();
            $table->string('ref_id',50)->nullable();
            $table->string('service_charges',50)->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('on_demand_cloud_computing',10)->default('ACTIVE');
            $table->string('multi_bit_visual_redux',10)->default('ACTIVE');
            $table->string('scan_reading',10)->default('ACTIVE');
            $table->string('googly',10)->default('ACTIVE');
            $table->text('img_url')->nullable();
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
        Schema::dropIfExists('payment_gateway');
    }
}
