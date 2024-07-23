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
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('name', 50);
            $table->string('lName', 150);
            $table->string('address', 255)->nullable();
            $table->string('phone', 25);
            $table->string('dni', 25)->unique();
            $table->string('gender', 10)->nullable();
            $table->unsignedSmallInteger('age')->nullable();
            $table->date('birthDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
