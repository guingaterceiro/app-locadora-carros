<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{

    public function __construct(Carro $carro)
    {

        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelos');
        }
        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }
        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate($this->carro->rules(), $this->carro->feedback());

        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km
        ]);
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $carro = $this->carro
            ->with('modelo')
            ->find($id);

        if ($carro === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $carro = $this->carro->find($id);

        if ($carro === null) {
            return response()->json(['erro' => 'Não foi possível realizar a atualização, o recurso não existe!'], 404);
        }
        // Validação para métodos PATH
        if ($request->method() === 'PATH') {

            $regrasDinamicas = array();

            foreach ($carro->rules() as $input => $regra) {

                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $carro->feedback());
        } else {
            $request->validate($carro->rules(), $carro->feedback());
        }
     
        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(['erro' => 'Não foi possível excluir, o recurso não existe!'], 404);
        }

        $carro->delete();
        return response()->json(['msg' => 'O carro foi removida com sucesso!'], 200);
    }
}
