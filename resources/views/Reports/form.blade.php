@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Generar Gráficas de Ventas</h1>

        <!-- Formulario para seleccionar los filtros -->
        <form action="{{ route('generate.graph') }}" method="GET">
            @csrf

            <!-- Filtros: Fecha de inicio y fin -->
            <div class="mb-3">
                <label for="start_date" class="form-label">Fecha de inicio:</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Fecha de fin:</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>

            <!-- Filtro: Frecuencia -->
            <div class="mb-3">
                <label for="frequency" class="form-label">Frecuencia:</label>
                <select name="frequency" class="form-select" required>
                    <option value="daily">Diaria</option>
                    <option value="weekly">Semanal</option>
                    <option value="monthly">Mensual</option>
                </select>
            </div>

            <!-- Filtro: Productos -->
            <div class="mb-3">
                <label for="products" class="form-label">Productos:</label>
                <select name="products[]" class="form-select" multiple>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="chart_type" class="form-label">Tipo de gráfico:</label>
                <select name="chart_type" class="form-select" required>
                    <option value="line">Líneas</option>
                    <option value="bar">Barras</option>
                    <option value="pie">Torta</option>
                </select>
            </div>
            <!-- Botón para enviar el formulario -->
            <button type="submit" class="btn btn-primary">Generar Gráfica</button>
        </form>
    </div>
@endsection
