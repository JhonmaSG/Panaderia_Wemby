<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class GraficosController extends Controller
{
    function grafico()
    {
        // ...

        $chart_options = [
            'chart_title' => 'Users by months',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\User',
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
        ];
        $chart1 = new LaravelChart($chart_options);

        return view('Reports.reportes', compact('chart1'));
    }

    function ventas(Request $request)
    {
        $range = $request->input('range', 'daily'); // por defecto 'daily'
        $startDate = now();
        $endDate = now();

        // Definir el rango de fechas según la selección
        switch ($range) {
            case 'daily':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'weekly':
                $startDate = now()->startOfWeek(); // Comienza el lunes
                $endDate = now()->endOfWeek();     // Termina el domingo
                break;
            case 'monthly':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
        }

        $date1 = new DateTime($startDate);
        $date2 = new DateTime($endDate);
        $formattedDate1 = $date1->format('Y-m-d H:i:s');
        $formattedDate2 = $date2->format('Y-m-d H:i:s');
        // Obtener las ventas basadas en el rango de fechas
        $ventas = Venta::whereBetween('fecha_venta', [$formattedDate1, $formattedDate2])->get();

        // Opciones para el gráfico
        $chart_options = [
            'chart_title' => 'Ventas',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Venta',
            'group_by_field' => 'fecha_venta',
            'group_by_period' => ($range === 'weekly') ? 'week' : ($range === 'monthly' ? 'month' : 'day'),
            'chart_type' => 'bar',
        ];
        $chart = new LaravelChart($chart_options);
        ;
        // if ($range === "monthly") {
        //     $mes = 02;
        //     $anio = 2024;

        //     // Validar los datos (opcional)
        //     if (!$mes || !$anio) {
        //         // Manejar el caso en que no se proporcionen valores
        //         return redirect()->back()->with('error', 'Debes seleccionar un mes y año');
        //     }

        //     // Construir el rango de fechas
        //     $startDate = Carbon::create($anio, $mes, 1);
        //     $endDate = $startDate->copy()->endOfMonth();

        //     $ventas1 = Venta::whereBetween('fecha_venta', [$startDate, $endDate])->get();
        //     $chart->($ventas1);
        // }
        // Calcular el total de ventas
        switch ($range) {
            case 'daily':
                // Suma las ventas del día actual
                $total_ventas = Venta::whereDate('fecha_venta', now()->toDateString())
                    ->sum('total_venta');
                break;

            case 'weekly':
                // Suma las ventas de la semana actual
                $total_ventas = Venta::whereBetween('fecha_venta', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString()
                ])
                    ->sum('total_venta');
                break;

            case 'monthly':
                // Suma las ventas del mes actual
                $total_ventas = Venta::whereYear('fecha_venta', now()->year)
                    ->whereMonth('fecha_venta', now()->month)
                    ->sum('total_venta');
                break;

            default:
                // Suma todas las ventas si no hay rango específico
                $total_ventas = Venta::sum('total_venta');
                break;
        }

        // Calcular el stock total de inventario
        $stockInventario = Producto::sum('stock');
        // return $ventas;
        return view('Reports.reportes_ventas', compact('ventas', 'range', 'chart', 'total_ventas'));
    }

    function mostrarFormulario()
    {
        // Obtener todos los productos disponibles
        $productos = Producto::all();
        return view('Analisis.analisis', compact('productos'));
    }

    function generarGrafica(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'tipo_grafica' => 'required|string',
            'parametro' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'productos' => 'required|array',
        ]);

        // Extraer los datos validados
        $productosSeleccionados = $validated['productos'];
        $fechaInicio = Carbon::parse($validated['fecha_inicio']);
        $fechaFin = Carbon::parse($validated['fecha_fin']);
        $parametro = $validated['parametro'];

        // Obtener los productos seleccionados
        $productos = Producto::whereIn('id_producto', $productosSeleccionados)->get();

        // Dependiendo del parámetro seleccionado (diarias, semanales, mensuales), realizar la consulta adecuada
        if ($parametro === 'diaria') {
            $ventas = Venta::selectRaw('DATE(ventas.fecha_venta) as fecha, SUM(dv.cantidad * dv.precio_unitario) as total')
                ->join('detalle_venta as dv', 'ventas.num_factura', '=', 'dv.num_factura')
                ->whereIn('dv.id_producto', $productosSeleccionados)
                ->whereBetween('ventas.fecha_venta', [$fechaInicio, $fechaFin])
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();
        } elseif ($parametro === 'semanal') {
            $ventas = Venta::selectRaw('WEEK(v.fecha_venta) as semana, SUM(dv.cantidad * dv.precio_unitario) as total')
                ->join('detalle_venta as dv', 'ventas.num_factura', '=', 'dv.num_factura')
                ->whereIn('dv.id_producto', $productosSeleccionados)
                ->whereBetween('ventas.fecha_venta', [$fechaInicio, $fechaFin])
                ->groupBy('semana')
                ->orderBy('semana')
                ->get();
        } else {
            $ventas = Venta::selectRaw('MONTH(v.fecha_venta) as mes, SUM(dv.cantidad * dv.precio_unitario) as total')
                ->join('detalle_venta as dv', 'ventas.num_factura', '=', 'dv.num_factura')
                ->whereIn('dv.id_producto', $productosSeleccionados)
                ->whereBetween('ventas.fecha_venta', [$fechaInicio, $fechaFin])
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();
        }

        // Preparar los datos para el gráfico
        $labels = [];
        $totales = [];
        foreach ($ventas as $venta) {
            if ($parametro === 'diaria') {
                $labels[] = Carbon::parse($venta->fecha)->format('d/m/Y');
            } elseif ($parametro === 'semanal') {
                $labels[] = "Semana " . $venta->semana;
            } else {
                $labels[] = Carbon::parse("2020-{$venta->mes}-01")->format('F');
            }
            $totales[] = $venta->total;
        }

        // Pasar los datos a la vista
        return view('Analisis.grafico', compact('labels', 'totales', 'validated'));
    } 
}
