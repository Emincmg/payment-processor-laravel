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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('channel');
            $table->string('currency')->nullable();
            $table->double('amount');
            $table->string('status')->default('pending');
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('declined_at')->nullable();
            $table->unsignedInteger('tries')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
