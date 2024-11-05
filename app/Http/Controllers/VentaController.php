<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::with('detalleVenta.producto');

        if ($request->filled('search')) {
            $search = $request->search;
            $ventas->where(function ($query) use ($search) {
                $query->where('num_factura', 'LIKE', "%{$search}%")
                      ->orWhere('documento_cliente', 'LIKE', "%{$search}%")
                      ->orWhere('fecha_venta', 'LIKE', "%{$search}%");
            });
        }
    
        $ventas = $ventas->get();
        return view('index_ventas', compact('ventas'));
    }

    public function show($id)
    {
        $venta = Venta::with(['detalleVenta.producto', 'cajero'])->findOrFail($id);
        return view('ver_venta', compact('venta'));
    }


    public function create()
    {
        $cajeros = User::where('rol', 1)->get(); // Cajeros
        $productos = Producto::where('stock', '>', 0)->get(); // Productos disponibles

        return view('registrar_venta', compact('cajeros', 'productos'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'cajero_id' => 'required|exists:users,id',
            'documento_cliente' => 'required',
            'productos' => 'required|array',
            'cantidades' => 'required|array'
        ]);
        try {
            $venta = new Venta([
                'num_factura' => 'FAC-' . time(),
                'fecha_venta' => now(),
                'documento_cliente' => $request->documento_cliente,
                'id_cajero' => $request->cajero_id,
                'total_venta' => array_sum(array_map(function ($cantidad, $precio) {
                    return $cantidad * $precio;
                }, $request->cantidades, $request->precios))
            ]);
            $venta->save();

            foreach ($request->productos as $index => $producto_id) {
                $venta->detalleVenta()->create([
                    'num_factura' => $venta->num_factura,
                    'id_producto' => $producto_id,
                    'cantidad' => $request->cantidades[$index],
                    'precio_unitario' => $request->precios[$index]
                ]);

                $producto = Producto::find($producto_id);
                $producto->decrement('stock', $request->cantidades[$index]);
            }

            return redirect()->route('ventas.create')->with('success', 'Venta creada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('ventas.create')->withErrors('Error: ' . $e->getMessage());
        }
    }
}
