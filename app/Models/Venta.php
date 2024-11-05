<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = "ventas";
    protected $fillable = [
        'num_factura',
        'fecha_venta',
        'documento_cliente',
        'id_cajero',
        'total_venta'
    ];

    public function detalleVenta()
    {
        return $this->hasMany(Detalle_Venta::class, 'num_factura', 'num_factura');
    }
}
