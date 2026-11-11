<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $tables = ['sales', 'sales_allowed_cities'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            $hasPrimary = DB::table('information_schema.table_constraints')
                ->where('table_schema', DB::raw('DATABASE()'))
                ->where('table_name', $table)
                ->where('constraint_type', 'PRIMARY KEY')
                ->exists();

            if (!$hasPrimary) {
                DB::statement("ALTER TABLE {$table} MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)");
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE {$table} MODIFY id BIGINT UNSIGNED NOT NULL, DROP PRIMARY KEY");
        }
    }
};
