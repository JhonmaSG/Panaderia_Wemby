@extends('layouts.app')

@section('content')
<h1>Gráfico de Ventas</h1>
<div style="width: 80%; margin: 50px auto;">
    <canvas id="graficoVentas"></canvas>
</div>
@endsection

@section('scripts')
<script>
    var labels = @json($labels); // Las fechas, semanas o meses
    var totales = @json($totales); // El total de ventas por cada fecha

    var ctx = document.getElementById('graficoVentas').getContext('2d');
    var graficoVentas = new Chart(ctx, {
        type: '{{ $validated['tipo_grafica'] }}',  // Usar el tipo de gráfica seleccionado
        data: {
            labels: labels,
            datasets: [{
                label: 'Ventas Totales',
                data: totales,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection