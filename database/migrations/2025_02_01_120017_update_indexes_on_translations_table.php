<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('translations', function (Blueprint $table) {
            // Check if 'key' index exists before adding
            if (!$this->indexExists('translations', 'translations_key_index')) {
                $table->index('key');
            }

            // Check if 'tag' index exists before adding
            if (!$this->indexExists('translations', 'translations_tag_index')) {
                $table->index('tag');
            }
        });
    }

    public function down()
    {
        Schema::table('translations', function (Blueprint $table) {
            // Check before dropping index to avoid errors
            if ($this->indexExists('translations', 'translations_key_index')) {
                $table->dropIndex(['key']);
            }

            if ($this->indexExists('translations', 'translations_tag_index')) {
                $table->dropIndex(['tag']);
            }
        });
    }

    private function indexExists($table, $index)
    {
        return DB::select(
            "SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index]
        );
    }
};

