<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueIndexFromCustomersPhone extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_phone_unique');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unique('phone');
        });
    }
}
