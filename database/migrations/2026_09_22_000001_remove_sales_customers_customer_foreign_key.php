<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $constraint = DB::selectOne(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.REFERENTIAL_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = 'sales_customers'
               AND CONSTRAINT_NAME = 'sales_customers_customer_id_foreign'"
        );

        if ($constraint) {
            DB::statement('ALTER TABLE sales_customers DROP FOREIGN KEY sales_customers_customer_id_foreign');
        }
    }

    public function down(): void
    {
        $constraint = DB::selectOne(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.REFERENTIAL_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = 'sales_customers'
               AND CONSTRAINT_NAME = 'sales_customers_customer_id_foreign'"
        );

        if (!$constraint) {
            DB::statement('ALTER TABLE sales_customers ADD CONSTRAINT sales_customers_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers (id)');
        }
    }
};