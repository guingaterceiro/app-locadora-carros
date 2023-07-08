<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules()
    {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,' . $this->id . '|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs'=> 'required|boolean'
        ];
    }

    public function feedback()
    {
        return [
            'exists' => 'Não foi encontrado a marca!',
            'required' => 'O campo :attribute é obrigatório!',
            'nome.unique' => 'O nome do modelo já existe!',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres!',
            'imagem.mimes' => 'O arquivo deve ser do tipo: .png, .jpg ou .jpeg',
        ];
    }

    public function marca(){
        // Uma marca pertence a vários modelos
        return $this->belongsTo('App\Models\Marca');
    }
}
