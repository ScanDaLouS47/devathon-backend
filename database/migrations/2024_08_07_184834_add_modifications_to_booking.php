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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('total_capacity');
            $table->unsignedInteger('persons');
            $table->unsignedBigInteger('shift_id');
            $table->text('adicional_info')->nullable();
            $table->boolean('allergens');
            // $table->dropColumn('table_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('total_capacity');
            $table->dropColumn('persons');
            $table->dropColumn('shift_id');
            $table->dropColumn('adicional_info');
            $table->dropColumn('allergens');
            $table->unsignedBigInteger('table_id');
        });
    }
};
