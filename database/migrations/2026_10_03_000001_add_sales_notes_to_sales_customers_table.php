<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalesNotesToSalesCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('sales_customers', function (Blueprint $table) {
            $table->text('sales_notes')->nullable()->after('notes');
        });
    }

    public function down()
    {
        Schema::table('sales_customers', function (Blueprint $table) {
            $table->dropColumn('sales_notes');
        });
    }
}
