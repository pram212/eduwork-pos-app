<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->char('code')->unique();
            $table->char('payment_status');
            $table->char('acceptance_status');
            $table->foreignId('supplier_id')->constrained();
            $table->date('payment_deadline');
            $table->bigInteger('product_price');
            $table->bigInteger('shipping_cost')->default(0);
            $table->bigInteger('grand_total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
