<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Definindo os campos que podem ser preenchidos (mass assignment)
    protected $fillable = [
        'title', 
        'description', 
        'date', 
        'location', 
        'organizer_id',
        'main_image', // Adicionando o campo 'main_image' no mass assignment
        'other_images' // Adicionando o campo 'other_images' no mass assignment
    ];

    // Definindo o cast para o campo 'other_images', pois ele é um campo JSON
    protected $casts = [
        'other_images' => 'array',
    ];

    /**
     * Relacionamento: um evento pertence a um organizador (usuário)
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Relacionamento: um evento pode ter muitas inscrições (participantes)
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}