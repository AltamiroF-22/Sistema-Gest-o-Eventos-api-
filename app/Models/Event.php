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
        'organizer_id'
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