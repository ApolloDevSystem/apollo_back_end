<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'endereco';
    protected $fillable = ['IDEndereco', 'bairro', 'logradouro', 'numero', 'referencia', 'complemento', 'CEP', 'UF', 'cidade'];

    public function funcionarios()
    {
        return $this->belongsToMany(Funcionario::class, 'endereco_funcionario', 'IDFuncionario', 'IDEndereco');
    }
}
