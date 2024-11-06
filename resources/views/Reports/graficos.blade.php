@extends('layouts.app')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Gráficas de Ventas</h1>

        <!-- Botón para volver al formulario -->
        <a href="{{ route('form') }}" class="btn btn-secondary mb-3">Volver a los filtros</a>

        <!-- Contenedor para la gráfica -->
        <div class="card">
            <div class="card-body">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: '{{ $chartType }}', // Usamos el tipo de gráfico pasado desde el controlador
        data: {
            labels: @json($labels), // Etiquetas de las fechas (e.g., días, semanas, meses)
            datasets: [{
                label: 'Ventas Totales',
                data: @json($values), // Datos de las ventas totales
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: '{{ $chartType == "line" ? "false" : "true" }}' // Si es gráfico de líneas, no se llena el área
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `Ventas: $${tooltipItem.raw}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
