<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    public function sp_GetAllProducten()
    {
        return DB::select('CALL sp_GetAllProducten()');
    }

    public function sp_CreateProduct($naam, $beschrijving, $prijs)
    {
        $row = DB::selectOne(
            'CALL sp_CreateProduct(:naam, :beschrijving, :prijs)',
            [
                'naam' => $naam,
                'beschrijving' => $beschrijving,
                'prijs' => $prijs,
            ]
        );

        return $row->new_id;
    }

    public function sp_DeleteProduct($id)
    {
        $row = DB::selectOne('CALL sp_DeleteProduct(:id)', ['id' => $id]);

        return $row->affected;
    }

    public function sp_GetProductById($id)
    {
        return DB::selectOne('CALL sp_GetProductById(:id)', ['id' => $id]);
    }

    public function sp_UpdateProduct($id, $naam, $beschrijving, $prijs)
    {
        $row = DB::selectOne(
            'CALL sp_UpdateProduct(:id, :naam, :beschrijving, :prijs)',
            [
                'id' => $id,
                'naam' => $naam,
                'beschrijving' => $beschrijving,
                'prijs' => $prijs,
            ]
        );

        return $row->affected ?? 0;
    }
}
