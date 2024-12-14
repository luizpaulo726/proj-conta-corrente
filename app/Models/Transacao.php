<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';
    public $timestamps = false;
    protected $fillable = ['conta_id', 'tipo', 'valor', 'descricao'];

    public function conta()
    {
        return $this->belongsTo(Conta::class);
    }
}
