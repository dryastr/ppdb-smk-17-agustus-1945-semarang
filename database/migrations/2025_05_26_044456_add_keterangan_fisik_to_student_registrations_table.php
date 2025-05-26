<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->text('keterangan_fisik_tato')->nullable()->after('keterangan_status');
            $table->text('keterangan_fisik_tindik')->nullable()->after('keterangan_fisik_tato');
            $table->text('keterangan_fisik_butawarna')->nullable()->after('keterangan_fisik_tindik');
            $table->text('keterangan_fisik_tinggi_berat')->nullable()->after('keterangan_fisik_tindik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn('keterangan_fisik_tato');
            $table->dropColumn('keterangan_fisik_tindik');
            $table->dropColumn('keterangan_fisik_butawarna');
            $table->dropColumn('keterangan_fisik_tinggi_berat');
        });
    }
};
