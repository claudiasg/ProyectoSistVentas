<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
        return view('auth/login');
});

Route::get('/hola', function () {
    return 'Hola Mundo';
});

Route::get('/objetos/caracteristicas', function () {
    return 'El id del objeto es:' . $_GET['id'];
});

Route::get('/objeto/{id}', function ($id) {
    return "El id del objeto es: {$id}";
})->where('id', '[0-9]+');;

Route::get('/objeto/nuevo', function () {
    return "Crear nuevo objeto";
});


Route::get('/alias/{nombrep}/{aliasp?}', function ($nombrep, $aliasp = null) {
    if($aliasp){
    	return "El pruducto {$nombrep}, tiene el alias de {$aliasp}";
    }else{
    	return "El pruducto {$nombrep}, no tiene alias";
    }
});
Route::resource('almacen/categoria','CategoriaController');
Route::resource('almacen/articulo','ArticuloController');
Route::resource('ventas/cliente','ClienteController');
Route::resource('compras/proveedor','ProveedorController');
Route::resource('compras/ingreso','IngresoController');
Route::resource('ventas/venta','VentaController');
Route::auth();
Route::get('/logout', 'Auth\LoginController@logout');
Route::resource('acceso/usuarios','UserController');
Route::get('/acercaDe', function () {
        return view('acercaDe/index');
});
//Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');
