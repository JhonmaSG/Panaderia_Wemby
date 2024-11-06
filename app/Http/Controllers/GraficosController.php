<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\IsBoss;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class GraficosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', IsBoss::class]);
    }
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

    public function showForm()
    {
        // Obtener todos los productos para que el usuario pueda seleccionar
        $productos = Producto::all();
        return view('reports.form', compact('productos'));
    }

    // Método para generar la gráfica
    public function generateGraph(Request $request)
{
    // Validación de los parámetros de entrada
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'frequency' => 'required|in:daily,weekly,monthly',
        'products' => 'nullable|array',
        'chart_type' => 'required|in:line,bar,pie', // Aseguramos que el tipo de gráfico sea uno de los tres
    ]);

    // Filtrar ventas según el rango de fechas
    $ventasQuery = Venta::whereBetween('fecha_venta', [$request->start_date, $request->end_date]);

    // Filtrar productos si es necesario
    if ($request->has('products') && !empty($request->products)) {
        // Obtener los detalles de las ventas que correspondan a los productos seleccionados
        $ventasQuery->whereHas('detalleVenta', function ($query) use ($request) {
            $query->whereIn('id_producto', $request->products);
        });
    }

    // Obtener las ventas agrupadas según la frecuencia seleccionada (diaria, semanal, mensual)
    $ventasData = $ventasQuery->get()->groupBy(function($venta) use ($request) {
        switch ($request->frequency) {
            case 'daily':
                return Carbon::parse($venta->fecha_venta)->format('Y-m-d');
            case 'weekly':
                return Carbon::parse($venta->fecha_venta)->startOfWeek()->format('Y-W');
            case 'monthly':
                return Carbon::parse($venta->fecha_venta)->format('Y-m');
        }
    });

    // Preparar los datos para la gráfica
    $labels = $ventasData->keys();
    $values = $ventasData->map(function($group) {
        return $group->sum('total_venta');
    });
    $chartType  = $request->chart_type;
    // Pasar los datos y el tipo de gráfico a la vista
    return view('Reports.graficos', compact('labels', 'values', 'chartType'));
}

}
