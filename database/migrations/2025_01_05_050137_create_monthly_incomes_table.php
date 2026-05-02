<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('income')->unsigned();
            $table->date('collect_date');
            $table->integer('total_visits')->unsigned();
            $table->integer('completed_visits')->unsigned();
            $table->integer('open_visits')->unsigned();
            $table->integer('delayed_visits')->unsigned();
            $table->integer('canceled_visits')->unsigned();
            $table->integer('total_orders')->unsigned();
            $table->integer('total_reorders')->unsigned();
            $table->integer('total_new_orders')->unsigned();
            $table->integer('total_surveys')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_incomes');
    }
};
