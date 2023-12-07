<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $fillable = ['IDCliente', 'nome', 'numero', 'cpf', 'email'];
    protected $primaryKey = 'IDCliente';

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'endereco_cliente', 'IDCliente', 'IDEndereco');
    }
}
