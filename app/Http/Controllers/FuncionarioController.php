<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class FuncionarioController extends Controller
{

    private $enderecoController;

    public function __construct(EnderecoController $enderecoController)
    {
        $this->enderecoController = $enderecoController;
    }


    public function index()
    {
        return Funcionario::all();
    }

    public function store(Request $request)
    {

        $request->validate([
            'funcionario.nome' => 'required',
            'funcionario.numero' => 'required',
            'funcionario.email' => 'required|email',
            'funcionario.cpf' => 'required',
            'funcionario.diaria' => 'required',
        ]);

        $enderecosSalvos = false;

        try {
            DB::beginTransaction();

            $funcionario = Funcionario::create([
                'nome' => $request->input('funcionario.nome'),
                'numero' => $request->input('funcionario.numero'),
                'email' => $request->input('funcionario.email'),
                'cpf' => $request->input('funcionario.cpf'),
                'diaria' => $request->input('funcionario.diaria'),
            ]);

            if ($request->has('funcionario.enderecos') && is_array($request->input('funcionario.enderecos'))) {
                foreach ($request->input('funcionario.enderecos') as $enderecoData) {
                    $endereco = $this->enderecoController->store($enderecoData);

                    if ($endereco) {
                        $funcionario->enderecos()->attach($endereco->IDEndereco);
                        $enderecosSalvos = true;
                    } else {
                        $enderecosSalvos = false;
                        break; // Se houver um erro em um endereço, interrompemos o loop
                    }
                }
            } else {
                return response()->json(['error' => 'Erro no endereco a ser cadastrado'], 500);
            }

            if ($enderecosSalvos) {
                DB::commit();
                return response()->json(['id' => $funcionario->IDFuncionario], 201);
            } else {
                DB::rollback();
                return response()->json(['error' => 'Nao salvou o endereco'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar funcionário e endereços: ' . $e->getMessage());
            DB::rollback();
            return response()->json(['error' => 'Erro ao criar funcionário e endereços. ' . $e->getMessage()], 500);
        }
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
      //  $cpfLimpo = str_replace([' ', "'", '"'], '', $cpf);
        $funcionario = Funcionario::where('cpf', $cpf)->get();
        return response()->json(['funcionarios' => $funcionario]);
    }
}
