<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = "productos";
    protected $fillable = ['codigo_producto', 'nombre', 'id_categoria', 'precio', 'stock'];

    public function insumos()
    {
        return $this->belongsToMany(Insumos::class, 'productos_insumos', 'id_producto', 'id_insumo')->withPivot('cantidad_usada');
    }

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'proveedores_productos', 'id_producto', 'id_proveedor');
    }
}
