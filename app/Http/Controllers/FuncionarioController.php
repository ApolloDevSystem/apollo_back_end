<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuncionarioController extends Controller
{

    public function index()
    {
        return Funcionario::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'numero' => 'required',
            'email' => 'required|email',
            'cpf' => 'required',
            'diaria' => 'required',
            'enderecos' => 'array',
        ]);

        try {
            DB::beginTransaction();

            $funcionario = Funcionario::create([
                'nome' => $request->nome,
                'numero' => $request->numero,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'diaria' => $request->diaria,
            ]);

            if ($request->has('enderecos') && is_array($request->enderecos)) {
                foreach ($request->enderecos as $enderecoData) {
                    $EndController = new EnderecoController();
                    $endereco = $EndController->store($enderecoData);

                    $funcionario->enderecos()->attach($endereco->id);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erro ao criar funcionário e endereços.'], 500);
        }

        return response()->json(['id' => $funcionario->id], 201);
    }


    public function show($id)
    {
        return Funcionario::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->update($request->all());
        return $funcionario;
    }

    public function destroy($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->delete();
        return response()->json(['message' => 'Funcionario excluído com sucesso']);
    }

    public function buscaPorCpf($cpf)
    {
        $cpfLimpo = str_replace([' ', "'", '"'], '', $cpf);
        $funcionario = Funcionario::where('cpf', $cpfLimpo)->get();
        return response()->json(['funcionarios' => $funcionario]);
    }
}
