<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Venta;
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

        // Calcular el total de ventas
        $total_ventas= Venta::sum('total_venta');

        // Calcular el stock total de inventario
        $stockInventario = Producto::sum('stock');
        // return $ventas;
        return view('Reports.reportes_ventas', compact('ventas', 'range', 'chart', 'total_ventas'));
    }
}
