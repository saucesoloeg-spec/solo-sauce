<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeputyIdToOrdersAndVehiclesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('deputy_id')->nullable()->after('driver_id');
            $table->foreign('deputy_id')->references('id')->on('deputies')->nullOnDelete();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('deputy_id')->nullable()->after('driver_id');
            $table->foreign('deputy_id')->references('id')->on('deputies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['deputy_id']);
            $table->dropColumn('deputy_id');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['deputy_id']);
            $table->dropColumn('deputy_id');
        });
    }
}
