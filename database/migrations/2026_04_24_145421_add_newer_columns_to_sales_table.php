<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewerColumnsToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('country_odoo_id')->nullable()->after('city');
            $table->integer('state_odoo_id')->nullable()->after('country_odoo_id');
            $table->integer('city_odoo_id')->nullable()->after('state_odoo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('country_odoo_id');
            $table->dropColumn('state_odoo_id');
            $table->dropColumn('city_odoo_id');
        });
    }
}
