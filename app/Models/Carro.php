<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules()
    {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required',
        ];
    }

    public function feedback()
    {
        return [
            'exists' => 'Não foi encontrado o modelo!',
            'required' => 'O campo :attribute é obrigatório!',
        ];
    }

    public function modelo()
    {
        // Um carro pertence a vários modelos
        return $this->belongsTo('App\Models\Modelo');
    }
}
