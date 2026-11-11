<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('information_schema.table_constraints')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', 'sales_customers')
            ->where('constraint_type', 'PRIMARY KEY')
            ->exists()) {
            return;
        }

        DB::statement('ALTER TABLE sales_customers MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE sales_customers MODIFY id BIGINT UNSIGNED NOT NULL, DROP PRIMARY KEY');
    }
};