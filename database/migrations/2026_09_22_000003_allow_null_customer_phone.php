<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AllowNullCustomerPhone extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE customers MODIFY phone VARCHAR(255) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE customers MODIFY phone VARCHAR(255) NOT NULL');
    }
}