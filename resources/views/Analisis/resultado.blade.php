@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Resultado del análisis: {{ ucfirst(str_replace('_', ' ', $tipoAnalisis)) }}</h2>

        @if (!$resultado)
            <p>No se encontraron resultados para el análisis seleccionado.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        @if ($tipoAnalisis == 'ventas_altas')
                            <th>Número de factura</th>
                            <th>Fecha de venta</th>
                            <th>Total de venta</th>
                        @elseif($tipoAnalisis == 'productos_mas_vendidos' || $tipoAnalisis == 'stock_bajo')
                            <th>Nombre del producto</th>
                            <th>Cantidad</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resultado as $item)
                        <tr>
                            @if ($tipoAnalisis == 'ventas_altas')
                                <td>{{ $item->num_factura }}</td>
                                <td>{{ $item->fecha_venta }}</td>
                                <td>${{ number_format($item->total_venta, 2) }}</td>
                            @elseif($tipoAnalisis == 'productos_mas_vendidos' || $tipoAnalisis == 'stock_bajo')
                                @if (isset($categoria))
                                    @if ($item->id_categoria == $categoria)
                                        <td>{{ $item->nombre }}</td>
                                        <td>{{ $item->stock }}</td>
                                    @endif
                                @else
                                    <td>{{ $item->nombre }}</td>
                                    <td>{{ $item->stock }}</td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('analisis.form') }}" class="btn btn-secondary">Volver</a>
    </div>
@endsection
