<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('table_sales');
        Schema::create('table_sales', function (Blueprint $table) {
           
            $table->id();
            $table->string('invoice')->unique();
            $table->timestamp('sales_date')->useCurrent();
            //---------------//
            //field tambahan untuk payment Midtrans
            $table->string('payment_method')->default('cash'); // 'cash' atau 'qris'
            $table->string('status')->default('success'); // 'pending', 'success', 'failed'
            $table->string('snap_token')->nullable(); // Untuk menyimpan token dari Midtrans
            //---------------//
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('change', 15, 2)->default(0);
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
        Schema::dropIfExists('table_sales');
    }
};
