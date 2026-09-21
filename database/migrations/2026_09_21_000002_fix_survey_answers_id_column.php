<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE survey_answers MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE survey_answers MODIFY id BIGINT UNSIGNED NOT NULL, DROP PRIMARY KEY');
    }
};