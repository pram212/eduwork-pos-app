<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->char('voucher')->unique();
            $table->foreignId('type_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->bigInteger('payment')->nullable();
            $table->bigInteger('refund')->nullable();
            $table->char('supplier')->nullable();
            $table->char('description')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
