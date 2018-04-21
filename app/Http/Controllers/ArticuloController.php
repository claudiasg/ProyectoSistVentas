<?php

namespace SisFramework\Http\Controllers;
//session_start();
use Illuminate\Http\Request;
use SisFramework\Articulo;
use Illuminate\Support\Facades\Redirect;//Libreria para hacer redireccion
use Illuminate\Support\Facades\input;
use SisFramework\Http\Requests\ArticuloFormRequest;
use DB;

class ArticuloController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

        public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));
            $articulos=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.descripcion','a.imagen','a.estado')
            ->where('a.nombre','LIKE','%'.$query.'%')
            ->orwhere('a.codigo','LIKE','%'.$query.'%')
            ->orderBy('a.idarticulo','desc')
            ->paginate(7);
            return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);
        }
    }

    public function create(){
    	$categorias=DB::table('categoria')->where('condicion', '=','1')->get();
    	return view("almacen.articulo.create", ["categorias"=>$categorias]);
    }

    public function store(Request $request){
        $nameFile="";
    	$articulo = new Articulo;
    	$articulo->idcategoria=$request->get('idcategoria');
    	$articulo->codigo=$request->get('codigo');
    	$articulo->nombre=$request->get('nombre');
    	$articulo->stock=$request->get('stock');
    	$articulo->descripcion=$request->get('descripcion');
    	$articulo->estado='Activo';

    	if(Input::hasFile('imagen')){
    		$file=Input::File('imagen');
    		$file->move(public_path().'/imagenes/articulos/', $file->getClientOriginalName());
            $nameFile= $file->getClientOriginalName();
    	}
        $articulo->imagen=$nameFile;
    	$articulo->save();
    	return Redirect::to('almacen/articulo');
    }

    public function show($id){
    	return view("almacen.articulo.show", ["articulo"=>Articulo::findOrFail($id)]);
    }

    public function edit($id)
    {
    	$articulo = Articulo::findOrFail($id);
    	$categorias=DB::table('categoria')->where('condicion', '=','1')->get();
        return view("almacen.articulo.edit",["articulo"=>$articulo, "categorias"=>$categorias]);
    }

    public function update(Request $request,$id)
    {
        $nameFile="";

        $articulo=Articulo::findOrFail($id);

        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');

        if(Input::hasFile('imagen')){
            $file=Input::File('imagen');
            $file->move(public_path().'/imagenes/articulos/', $file->getClientOriginalName());
            $nameFile= $file->getClientOriginalName();
        }

        $articulo->imagen=$nameFile;
        $articulo->update();
        return Redirect::to('almacen/articulo');

    }

    public function destroy($id){
    	$articulo=Articulo::findOrFail($id);
    	$articulo->estado='Inactivo';
		$articulo->update();
    	return Redirect::to('almacen/articulo');
    }
}
