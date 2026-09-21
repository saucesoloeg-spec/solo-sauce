<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsImportedToCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_imported')->default(false)->after('via');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_imported');
        });
    }
}
