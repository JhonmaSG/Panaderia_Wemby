<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = "proveedores";

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'proveedores_productos', 'id_proveedor', 'id_producto');
    }
}
