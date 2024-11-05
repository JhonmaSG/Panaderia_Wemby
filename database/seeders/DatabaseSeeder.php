<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Insumo;
use App\Models\Insumos;
use App\Models\Producto;
use App\Models\Productos_insumo;
use App\Models\Proveedores_insumo;
use App\Models\Proveedor;
use App\Models\Proveedores_producto;
use App\Models\Rol;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $rol1 = new Rol();
        $rol1->nombre_rol = "Dueño";
        $rol1->save();

        $rol2 = new Rol();
        $rol2->nombre_rol = "Panadero";
        $rol2->save();

        $rol3 = new Rol();
        $rol3->nombre_rol = "Cajero";
        $rol3->save();

        $usuario = new User();
        $usuario->name = "Admin";
        $usuario->email = 'admin@gmail.com';
        $usuario->password = Hash::make("12345678");
        $usuario->rol = 1;
        $usuario->save();

        $categoria = new Categoria();
        $categoria->nombre_categoria = "Panadería";
        $categoria->save();

        $categoria2 = new Categoria();
        $categoria2->nombre_categoria = "Repostería";
        $categoria2->save();

        $categoria3 = new Categoria();
        $categoria3->nombre_categoria = "Lácteos";
        $categoria3->save();

        $proveedores = new Proveedor();
        $proveedores->nombre = "Proveedor Panes";
        $proveedores->contacto = "contacto@panes.com";
        $proveedores->save();

        $proveedores2 = new Proveedor();
        $proveedores2->nombre = "Proveedor Dulces";
        $proveedores2->contacto = "contacto@dulces.com";
        $proveedores2->save();

        $proveedores3 = new Proveedor();
        $proveedores3->nombre = "Proveedor Leche";
        $proveedores3->contacto = "contacto@leche.com";
        $proveedores3->save();

        $producto1 = new Producto();
        $producto1->codigo_producto = 'PAN001';
        $producto1->nombre = 'Pan Francés';
        $producto1->id_categoria = 1;
        $producto1->precio = 1.50;
        $producto1->stock = 100;
        $producto1->save();

        $producto2 = new Producto();
        $producto2->codigo_producto = 'DUL001';
        $producto2->nombre = 'Pastel de Chocolate';
        $producto2->id_categoria = 2;
        $producto2->precio = 3.00;
        $producto2->stock = 50;
        $producto2->save();

        $insumo1 = new Insumos();
        $insumo1->nombre_insumo = 'Harina';
        $insumo1->id_categoria = 1;
        $insumo1->stock = 1000;
        $insumo1->save();

        $insumo2 = new Insumos();
        $insumo2->nombre_insumo = 'Chocolate';
        $insumo2->id_categoria = 2;
        $insumo2->stock = 500;
        $insumo2->save();

        $insumo3 = new Insumos();
        $insumo3->nombre_insumo = 'Leche';
        $insumo3->id_categoria = 3;
        $insumo3->stock = 800;
        $insumo3->save();

        $proveedorInsumo1 = new Proveedores_insumo();
        $proveedorInsumo1->id_proveedor = 1;
        $proveedorInsumo1->id_insumo = 1;
        $proveedorInsumo1->save();

        $proveedorInsumo2 = new Proveedores_insumo();
        $proveedorInsumo2->id_proveedor = 2;
        $proveedorInsumo2->id_insumo = 2;
        $proveedorInsumo2->save();

        $proveedorInsumo3 = new Proveedores_insumo();
        $proveedorInsumo3->id_proveedor = 3;
        $proveedorInsumo3->id_insumo = 3;
        $proveedorInsumo3->save();

        $proveedorProducto1 = new Proveedores_producto();
        $proveedorProducto1->id_proveedor = 1;
        $proveedorProducto1->id_producto = 1;
        $proveedorProducto1->save();

        $proveedorProducto2 = new Proveedores_producto();
        $proveedorProducto2->id_proveedor = 2;
        $proveedorProducto2->id_producto = 2;
        $proveedorProducto2->save();

        $productoInsumo1 = new Productos_insumo();
        $productoInsumo1->id_producto = 1;
        $productoInsumo1->id_insumo = 1;
        $productoInsumo1->cantidad_usada = 2;
        $productoInsumo1->save();

        $productoInsumo2 = new Productos_insumo();
        $productoInsumo2->id_producto = 2;
        $productoInsumo2->id_insumo = 2;
        $productoInsumo2->cantidad_usada = 1;
        $productoInsumo2->save();

        $productoInsumo3 = new Productos_insumo();
        $productoInsumo3->id_producto = 2;
        $productoInsumo3->id_insumo = 3;
        $productoInsumo3->cantidad_usada = 1;
        $productoInsumo3->save();
    }
}
