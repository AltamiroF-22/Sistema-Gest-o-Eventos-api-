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
        Schema::create('events', function (Blueprint $table) {
            $table->id();  // ID do evento
            $table->string('title');  // Título do evento
            $table->text('description');  // Descrição do evento
            $table->dateTime('date');  // Data e hora do evento
            $table->string('location');  // Localização do evento
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');  // Referência ao organizador (usuário)
            $table->timestamps();  // Timestamps padrão (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};