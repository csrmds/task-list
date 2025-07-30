<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id',
        'resumo',
        'descricao',
        'agenda_inicio',
        'agenda_fim',
        'status',
        'responsavel',
        'categoria',
        'tags',
        'google_calendar_id',
        'google_calendar_link',
        'user_id',
        'create_at',
        'updated_at',
    ];
}
