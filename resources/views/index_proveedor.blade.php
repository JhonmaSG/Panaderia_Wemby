@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Listado de Proveedores</h2>
    <form action="{{ route('proveedores.index') }}" method="GET">
        <div class="form-group">
            <label for="search">Buscar proveedor:</label>
            <input type="text" class="form-control" id="search" name="search" style="width: 25%" placeholder="Nombre o código del producto">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Productos que provee</th>
                <th>Insumos que provee</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($proveedores as $proveedor)
            <tr>
                <td>{{ $proveedor->nombre }}</td>
                <td>{{ $proveedor->contacto }}</td>
                <td>
                    @foreach ($proveedor->productos as $producto)
                        <span class="badge bg-primary">{{ $producto->nombre }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach ($proveedor->insumos as $insumo)
                        <span class="badge bg-info">{{ $insumo->nombre_insumo }}</span>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
