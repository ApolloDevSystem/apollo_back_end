<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionario';
    protected $fillable = ['IDFuncionario', 'nome', 'numero', 'email', 'cpf', 'diaria'];
    protected $primaryKey = 'IDFuncionario';

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'endereco_funcionario', 'IDFuncionario', 'IDEndereco');
    }
}
