<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function __construct(Cliente $cliente)
    {

        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $clienteRepository = new ClienteRepository($this->cliente);

        // if ($request->has('atributos_modelos')) {
        //     $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
        //     $clienteRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        // } else {
        //     $clienteRepository->selectAtributosRegistrosRelacionados('modelos');
        // }
        if ($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }
        if ($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($clienteRepository->getResultado(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate($this->cliente->rules(), $this->cliente->feedback());

        $cliente = $this->cliente->create([
            'nome' => $request->nome,
        ]);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $cliente = $this->cliente
            // ->with('modelo')
            ->find($id);

        if ($cliente === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        return response()->json($cliente, 200);
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $cliente = $this->cliente->find($id);

        if ($cliente === null
        ) {
            return response()->json(['erro' => 'Não foi possível realizar a atualização, o recurso não existe!'], 404);
        }
        // Validação para métodos PATH
        if ($request->method() === 'PATH') {

            $regrasDinamicas = array();

            foreach ($cliente->rules() as $input => $regra) {

                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $cliente->feedback());
        } else {
            $request->validate($cliente->rules(), $cliente->feedback());
        }

        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(['erro' => 'Não foi possível excluir, o recurso não existe!'], 404);
        }

        $cliente->delete();
        return response()->json(['msg' => 'O cliente foi removido com sucesso!'], 200);
    }
}
