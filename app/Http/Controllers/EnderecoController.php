<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function index()
    {
        return Endereco::all();
    }

    public function store(Request $request)
    {
        $endereco = [
            'bairro' => $request->bairro,
            'logradouro' => $request->logradouro,
            'numero' => $request->numero,
            'referencia' => $request->referencia,
            'complemento' => $request->complemento,
            'CEP' => $request->cep,
            'UF' => $request->uf,
            'cidade' => $request->cidade
        ];

        //tratamento

        $enderecoCriado = Endereco::create($endereco);

        // Retorna o ID como resposta
        return $enderecoCriado->id;
    }

    public function show($id)
    {
        return Endereco::findOrFail($id);
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
