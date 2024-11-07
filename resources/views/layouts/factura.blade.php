<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura - {{ $numero_factura }}</title>
    <style>
        /* Puedes añadir estilos CSS aquí para el formato de la factura */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>{{ $nombre_panaderia }}</h2>
    <p>Número de Factura: {{ $numero_factura }}</p>
    <p>Cédula del Cliente: {{ $cliente_cedula }}</p>
    <p>Cajero: {{ $cajero }}</p>
    <p>Fecha de Venta: {{ $fecha_venta }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->pivot->cantidad }}</td>
                    <td>${{ $producto->precio }}</td>
                    <td>${{ $producto->pivot->cantidad * $producto->precio }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total a Pagar: ${{ $total_venta }}
    </div>

    <button onclick="window.print()" style="margin-top: 20px;">Imprimir Factura</button>
</body>
</html>
