<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('persons');
            $table->unsignedBigInteger('shift_id');
            $table->text('additional_info')->nullable();
            $table->boolean('allergens');
            $table->dropColumn('tableId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('persons');
            $table->dropColumn('shift_id');
            $table->dropColumn('additional_info');
            $table->dropColumn('allergens');
            $table->unsignedBigInteger('tableId');
        });
    }
};
