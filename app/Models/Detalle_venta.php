<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_venta extends Model
{
    use HasFactory;
    protected $table = "detalle_venta";
    
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
