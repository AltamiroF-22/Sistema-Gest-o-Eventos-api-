<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa (mass assignment)
     */
    protected $fillable = [
        'user_id',
        'event_id',
    ];

    /**
     * Relacionamento: uma inscrição pertence a um usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: uma inscrição pertence a um evento
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}