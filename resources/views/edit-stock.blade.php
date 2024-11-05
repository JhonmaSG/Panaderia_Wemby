@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Actualizar Stock del Producto: {{ $producto->nombre }}</h2>
    <div>
        <strong>Stock Anterior:</strong> {{ $producto->stock }}
    </div>
    <div>
        <strong>Insumos Usados:</strong>
        <ul>
        @foreach ($producto->insumos as $insumo)
            <li>{{ $insumo->nombre_insumo }}: {{ $insumo->pivot->cantidad_usada }} usado(s)</li>
        @endforeach
        </ul>
    </div>
    <div>
        <strong>Insumos Totales Disponibles:</strong>
        <ul>
        @foreach ($insumosTotales as $insumo)
            <li>{{ $insumo->nombre_insumo }}: {{ $insumo->stock }} disponible(s)</li>
        @endforeach
        </ul>
    </div>
    <form action="{{ route('productos.update-stock', $producto->id_producto) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="stock">Nuevo Stock</label>
            <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ $producto->stock }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
