<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumos extends Model
{
    use HasFactory;
    protected $table = "insumos";
    protected $primaryKey = 'id_insumo';

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'productos_insumos', 'id_insumo', 'id_producto')->withPivot('cantidad_usada');
    }
}
