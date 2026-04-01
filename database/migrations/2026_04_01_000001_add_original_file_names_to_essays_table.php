<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('essays', function (Blueprint $table) {
            $table->string('file_name')->nullable()->after('file');
            $table->string('ppt_file_name')->nullable()->after('ppt_file');
        });

        DB::table('essays')
            ->select(['id', 'file', 'ppt_file'])
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $fileName = !empty($row->file) ? basename((string) $row->file) : null;
                    $pptFileName = !empty($row->ppt_file) ? basename((string) $row->ppt_file) : null;

                    DB::table('essays')
                        ->where('id', $row->id)
                        ->update([
                            'file_name' => $fileName,
                            'ppt_file_name' => $pptFileName,
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('essays', function (Blueprint $table) {
            $table->dropColumn(['file_name', 'ppt_file_name']);
        });
    }
};
