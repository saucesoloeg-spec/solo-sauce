<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $constraint = DB::selectOne(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.REFERENTIAL_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = 'survey_answers'
               AND CONSTRAINT_NAME = 'survey_answers_customer_id_foreign'"
        );

        if ($constraint) {
            DB::statement('ALTER TABLE survey_answers DROP FOREIGN KEY survey_answers_customer_id_foreign');
        }
    }

    public function down(): void
    {
        $constraint = DB::selectOne(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.REFERENTIAL_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = 'survey_answers'
               AND CONSTRAINT_NAME = 'survey_answers_customer_id_foreign'"
        );

        if (!$constraint) {
            DB::statement('ALTER TABLE survey_answers ADD CONSTRAINT survey_answers_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE');
        }
    }
};