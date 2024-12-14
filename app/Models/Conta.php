<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Conta extends Model
{
    use HasFactory;

    protected $table = 'contas';
    public $timestamps = false;
    protected $fillable = ['numero', 'saldo', 'limite_credito'];

    public function saldoDisponivel()
    {
        return $this->saldo + $this->limite_credito;
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }

    public function numero()
    {
        return $this->numero; // Retorna o valor do atributo 'numero'
    }

}
