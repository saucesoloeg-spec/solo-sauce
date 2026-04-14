<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->timestamp('delivery_date');
            $table->decimal('amount_total', 10, 2);
            $table->decimal('amount_tax', 10, 2);
            $table->string('state');
            $table->string('payment_status');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('delivery_status');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
