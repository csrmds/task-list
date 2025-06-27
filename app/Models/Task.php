<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id',
        'descricao',
        'agenda_inicio',
        'agenda_fim',
        'status',
        'responsavel',
        'categoria',
        'tags',
        'create_at',
        'updated_at',
    ];
}
