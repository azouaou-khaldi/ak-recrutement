<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // Message envoyé par le formulaire de contact. lu passe à true quand l'admin l'ouvre (RG14)
    protected $fillable = ['nom', 'email', 'sujet', 'message', 'lu', 'reponse', 'repondu_le'];

    protected $casts = [
        'lu'         => 'boolean',
        'repondu_le' => 'datetime',
    ];
}
