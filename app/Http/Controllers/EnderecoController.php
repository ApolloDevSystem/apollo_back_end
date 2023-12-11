<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnderecoController extends Controller
{
    public function index()
    {
        return Endereco::all();
    }

    public function store($enderecoData)
    {
        try {
            // Lógica para validar e tratar os dados do endereço, se necessário

            // Criar o endereço
            $endereco = Endereco::create([
                'bairro' => $enderecoData['bairro'],
                'logradouro' => $enderecoData['logradouro'],
                'numero' => $enderecoData['numero'],
                'referencia' => $enderecoData['referencia'],
                'complemento' => $enderecoData['complemento'],
                'CEP' => $enderecoData['CEP'],
                'UF' => $enderecoData['UF'],
                'cidade' => $enderecoData['cidade'],
            ]);

            // Retorna o endereço criado
            return $endereco;
        } catch (\Exception $e) {
            // Log de erro (pode ser útil para depuração)
            Log::error('Erro ao criar endereço: ' . $e->getMessage());

            // Em caso de erro, retorna null
            return response()->json('Erro ao criar endereço: ' . $e->getMessage());
        }
    }

    public function showFunc($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $enderecos = $funcionario->enderecos;

        return $enderecos;
    }

    public function showCli($id)
    {
        $cliente = Cliente::findOrFail($id);
        $enderecos = $cliente->enderecos;

        return $enderecos == null ? response()->json('tal') : response()->json('tol');
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $enderecos = $user->enderecos;

        return $enderecos == null ? [] : response()->json('tol');
    }

    public function update(Request $request, $id)
    {
        $endereco = Endereco::findOrFail($id);
        $endereco->update($request->all());
        return $endereco;
    }

    public function destroy($id)
    {
        $endereco = Endereco::findOrFail($id);
        $endereco->delete();
        return response()->json(['message' => 'Endereco excluído com sucesso']);
    }
}
