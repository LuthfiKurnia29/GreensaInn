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
        Schema::table('ruangans', function (Blueprint $table) {
            $table->unsignedBigInteger('harga_per_jam')->default(0)->after('deskripsi');
            $table->string('tipe_ruangan')->nullable()->after('harga_per_jam');
            // $table->string('luas_ruangan')->nullable()->after('tipe_ruangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropColumn(['harga_per_jam', 'tipe_ruangan', 'luas_ruangan']);
        });
    }
};
