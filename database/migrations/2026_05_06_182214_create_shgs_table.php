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
        Schema::create('shgs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['SHG', 'FPG'])->default('SHG');
            $table->string('registration_number')->unique()->nullable();
            $table->date('formation_date')->nullable();
            $table->string('village')->nullable();
            $table->string('block')->nullable();
            $table->string('district')->nullable();
            $table->foreignId('leader_farmer_id')->nullable()->constrained('farmers')->onDelete('set null');
            $table->integer('total_members')->default(0);
            $table->string('bank_account')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shgs');
    }
};
