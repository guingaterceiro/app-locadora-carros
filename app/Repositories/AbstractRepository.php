<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository{

    // Método construtor
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    //Selecionando atributos
    public function selectAtributosRegistrosRelacionados($atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    // Fazendo filtros
    public function filtro($filtros)
    {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    // Selecionando atributos
    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }
    
    // Retornando o resultado
    public function getResultado()
    {
        return $this->model->get();
    }



}

?>