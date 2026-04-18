<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToSalesCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('visit_at');
            $table->boolean('survey')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_customers', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('survey_id');
        });
    }
}
