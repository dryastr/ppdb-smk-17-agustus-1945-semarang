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
            $table->string('fotokopi_kk')->nullable()->change();
            $table->string('fotokopi_ijazah')->nullable()->change();
            $table->string('fotokopi_akte')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->boolean('fotokopi_kk')->default(false)->change();
            $table->boolean('fotokopi_ijazah')->default(false)->change();
            $table->boolean('fotokopi_akte')->default(false)->change();
        });
    }
};
