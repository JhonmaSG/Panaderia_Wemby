@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Generar Gráficas de Ventas</h1>
    <form action="{{ route('generar.grafica') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <label for="tipo_grafica" class="col-sm-2 col-form-label">Tipo de Gráfica</label>
            <div class="col-sm-10">
                <select name="tipo_grafica" id="tipo_grafica" class="form-select">
                    <option value="line">Líneas</option>
                    <option value="bar">Barras</option>
                    <option value="torta">Torta</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="parametro" class="col-sm-2 col-form-label">Parámetro</label>
            <div class="col-sm-10">
                <select name="parametro" id="parametro" class="form-select">
                    <option value="diaria">Ventas Diarias</option>
                    <option value="semanal">Ventas Semanales</option>
                    <option value="mensual">Ventas Mensuales</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="fecha_inicio" class="col-sm-2 col-form-label">Fecha Inicio</label>
            <div class="col-sm-10">
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="fecha_fin" class="col-sm-2 col-form-label">Fecha Fin</label>
            <div class="col-sm-10">
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="productos" class="col-sm-2 col-form-label">Seleccionar Productos</label>
            <div class="col-sm-10">
                <select name="productos[]" id="productos" class="form-select" multiple>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Generar Gráfica</button>
            </div>
        </div>
    </form>
</div>
@endsection