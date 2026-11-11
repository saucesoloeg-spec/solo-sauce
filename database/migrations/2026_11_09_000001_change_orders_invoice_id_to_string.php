<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeOrdersInvoiceIdToString extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE orders MODIFY invoice_id VARCHAR(255) NULL');
    }

    public function down()
    {
        $hasNonNumericInvoiceId = DB::table('orders')
            ->whereNotNull('invoice_id')
            ->whereRaw("invoice_id NOT REGEXP '^[0-9]+$'")
            ->exists();

        if ($hasNonNumericInvoiceId) {
            throw new RuntimeException('Cannot convert non-numeric invoice IDs to unsigned integers.');
        }

        DB::statement('ALTER TABLE orders MODIFY invoice_id BIGINT UNSIGNED NULL');
    }
}
