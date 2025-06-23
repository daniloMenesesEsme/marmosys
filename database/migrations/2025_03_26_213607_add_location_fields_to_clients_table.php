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
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable()->after('estado');
            $table->unsignedBigInteger('establishment_type_id')->nullable()->after('location_id');
            
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('establishment_type_id')->references('id')->on('establishment_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropForeign(['establishment_type_id']);
            $table->dropColumn(['location_id', 'establishment_type_id']);
        });
    }
};
