<?php

namespace SisFramework\Http\Controllers;
//session_start();
use SisFramework\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SisFramework\Http\Request\CategoriaFormRequest;
use DB;

class CategoriaController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query = trim($request->get('searchText'));
    		$categorias = DB::table('categoria')->where('nombre', 'LIKE', '%'.$query.'%')
    		->where('condicion', '=', '1')
    		->orderBy('idcategoria', 'desc')
    		->paginate(7);
    		return view('almacen/categoria.index',["categorias"=>$categorias, "searchText"=>$query]);
    	}

    }

    public function create(){
    	return view("almacen.categoria.create");
    }

    public function store(Request $request){
    	$categoria = new Categoria;
    	$categoria->nombre=$request->get('nombre');
    	$categoria->descripcion=$request->get('descripcion');
    	$categoria->condicion=1;
    	$categoria->save();
    	return Redirect::to('almacen/categoria');
    }

    public function show($id){
    	return view("almacen.categoria.show", ["categoria"=>Categoria::findOrFail($id)]);
    }

    public function edit($id){
    	return view("almacen.categoria.edit", ["categoria"=>Categoria::findOrFail($id)]);
    }

    public function update(Request $request,$id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }

    public function destroy($id){
    	$categoria=Categoria::findOrFail($id);
    	$categoria->condicion = 0;
    	$categoria->update();
    	return Redirect::to('almacen/categoria');
    }

}
