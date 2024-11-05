{{-- resources/views/productos/index_producto.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Mensajes de Éxito -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Mensajes de Error -->
        @if (session('errors'))
            <div class="alert alert-danger" role="alert">
                @foreach (session('errors')->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <h1>Listado de Productos</h1>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">Registrar Producto</a>
        <form action="{{ route('productos.index') }}" method="GET">
            <div class="form-group">
                <label for="categoria">Filtrar por Categoría:</label>
                <select class="form-control" id="categoria" name="categoria" style="width: 25%">
                    <option value="">Sin filtrar</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}"
                            {{ request('categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                            {{ $categoria->nombre_categoria }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
        <form action="{{ route('productos.index') }}" method="GET">
            <div class="form-group">
                <label for="search">Buscar Producto:</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Nombre o código del producto">
            </div>
            <button type="submit" class="btn btn-primary">Buscar producto</button>
        </form>        
        <table class="table">
            <thead>
                <tr>
                    <th>Código del producto</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Proveedores</th>
                    <th>Insumos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->codigo_producto }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria->nombre_categoria ?? 'Sin categoría' }}</td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>
                            {{ $producto->stock }}
                        </td>
                        <td>
                            @if ($producto->proveedores->isEmpty())
                                <span class="text-muted">Sin proveedores</span>
                            @else
                                @foreach ($producto->proveedores as $proveedor)
                                    <span class="badge bg-primary">{{ $proveedor->nombre }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if ($producto->insumos->isEmpty())
                                <span class="text-muted">Sin insumos</span>
                            @else
                                @foreach ($producto->insumos as $insumo)
                                    <span class="badge bg-info">{{ $insumo->nombre_insumo }}:
                                        {{ $insumo->pivot->cantidad_usada }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('productos.edit-stock', $producto->id_producto) }}"
                                class="btn btn-info">Editar
                                Stock</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script>
            document.getElementById('categoria').addEventListener('change', function() {
                this.form.submit();
            });
        </script>
    </div>
@endsection
