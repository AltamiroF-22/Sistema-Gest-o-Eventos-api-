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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();  // ID da inscrição
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Referência ao usuário
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');  // Referência ao evento
            $table->timestamps();  // Timestamps padrão
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};