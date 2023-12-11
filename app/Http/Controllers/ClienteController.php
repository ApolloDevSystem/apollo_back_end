<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ClienteController extends Controller
{

    private $enderecoController;

    public function __construct(EnderecoController $enderecoController)
    {
        $this->enderecoController = $enderecoController;
    }

    public function index()
    {
        return Cliente::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cliente.nome' => 'required',
            'cliente.numero' => 'required',
            'cliente.email' => 'required|email',
            'cliente.cpf' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $enderecosSalvos = false;

        try {
            DB::beginTransaction();

            $cliente = Cliente::create([
                'nome' => $request->input('cliente.nome'),
                'numero' => $request->input('cliente.numero'),
                'email' => $request->input('cliente.email'),
                'cpf' => $request->input('cliente.cpf'),
            ]);

            if ($request->has('cliente.enderecos') && is_array($request->input('cliente.enderecos'))) {
                foreach ($request->input('cliente.enderecos') as $enderecoData) {
                    $endereco = $this->enderecoController->store($enderecoData);

                    if ($endereco) {
                        $cliente->enderecos()->attach($endereco->IDEndereco);
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
                return response()->json(['id' => $cliente->IDCliente], 201);
            } else {
                DB::rollback();
                return response()->json(['error' => 'Nao salvou o endereco'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar cliente e endereços: ' . $e->getMessage());
            DB::rollback();
            return response()->json(['error' => 'Erro ao criar cliente e endereços. ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return Cliente::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return $cliente;
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return response()->json(['message' => 'Cliente excluído com sucesso']);
    }

    public function buscaPorCpf($cpf)
    {
        $cpfLimpo = str_replace([' ', "'", '"'], '', $cpf);
        $cliente = Cliente::where('cpf', $cpfLimpo)->get();
        return response()->json(['clientes' => $cliente]);
    }
}
