<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixPersonalAccessTokensIdColumn extends Migration
{
    public function up()
    {
        if (DB::table('information_schema.table_constraints')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', 'personal_access_tokens')
            ->where('constraint_type', 'PRIMARY KEY')
            ->exists()) {
            return;
        }

        DB::statement('ALTER TABLE `personal_access_tokens` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE `personal_access_tokens` MODIFY `id` BIGINT UNSIGNED NOT NULL, DROP PRIMARY KEY');
    }
}