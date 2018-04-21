<?php

namespace SisFramework\Http\Controllers;
//session_start();
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\input;
use SisFramework\Http\Request\IngresoFormRequest;
use SisFramework\Ingreso;
use SisFramework\DetalleIngreso;
use DB;

Use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
     public function __construct(){
       $this->middleware('auth');
	}

        public function index(Request $request)
	    {
	        if ($request)
	        {
	            $query=trim($request->get('searchText'));
	            $ingresos=DB::table('ingreso as i')
	            ->join('persona as p', 'i.idproveedor', '=', 'p.idpersona')
	            ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
	            ->select('i.idingreso','i.fecha_hora','p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante','i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra)as total'))
	            ->where('i.num_comprobante', 'LIKE', '%'.$query.'%')
	            ->orderBy('i.idingreso', 'desc')
	            ->groupBy('i.idingreso','i.fecha_hora','p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante','i.impuesto', 'i.estado')
	            ->paginate(7);
            //  dd($ingresos);
	            return view('compras.ingreso.index', ["ingresos"=>$ingresos, "searchText"=>$query]);
	        }




          /* Comentar Aqui logica y descripccion del Query
          Esta funcion tiene un request el cual si existe se crea un variable $query que almacena dicho request en function del texto que se coloca en el
          campo con nombre searchText.
          Se crea una variable $ingresos que almacena una consulta tomando en cuenta lo siguiente:
          la tabla ingreso con el alias i, realiza una union con la tabla persona alias p, a traves de las llaves (tabla ingresos con idproveedor y persona
          cuyo id es idpersona), ademas se hace otro join con la tabla detalle_ingreso con las llaves correspondientes, para seleccionar las columnas o
          campos correpsondientes a las tablas ingresos, persona y se realiza una operacion con sum de la cantidad y precio_compra de la tabla detalle_ingreso
          y se coloca alias total a dicha suma,la consulta debe cumplir que el num_comprobante de la tabla ingreso debe contener lo que se coloca en la variable
          $query en funcion de lo que dice like, Se ordena descendentemente en funcion de id de ingreso, agrupando en funcion de diferntes campos de la tabla
          ingreso y persona. Se establece una paginacion para que muestre 7 datos por pagina y se retorna la vista que a punta a compras.ingreso.index.
          */

	    }

	    public function create(){
	    	$personas=DB::table('persona')->where('tipo_persona', '=', 'proveedor')->get();
	    	$articulos= DB::table('articulo as art')
	    	->select(DB::raw('CONCAT(art.codigo, " ", art.nombre) as articulo'), 'art.idarticulo')
	    	->where('art.estado','=','Activo')
	    	->get();
      //  dd($personas);
	    	return view('compras.ingreso.create',["personas"=>$personas, "articulos"=>$articulos]);
	    }/*Comentar Aqui logica y descripccion del Query
        Esta funcion permite registar un nuevoingreso para ello se almacena en la variable persona un consulta tomando en cuenta la tabla persona en la cual
        el campo tipo_persona de ser igual a proveedor.
        Ademas la variable $articulos almacena una consulta de la tabla articulo con alas art, donde se selecciona la concatenacion del codigo y nombre de
        articulos con un alias de articulo y el id de articulo, donde el estado del articulo debe ser igual a Activo y se retorna la vista compras.ingreso.create
      */

	    public function store(Request $request){
	    	try{
	    		DB::beginTransaction();

	    		$ingreso=new Ingreso;
	    		$ingreso->idproveedor=$request->get('idproveedor');
	    		$ingreso->tipo_comprobante=$request->get('tipo_comprobante');
	    		$ingreso->serie_comprobante=$request->get('serie_comprobante');
	    		$ingreso->num_comprobante=$request->get('num_comprobante');
	    		$mytime = Carbon::now('America/Mexico_City');
	    		$ingreso->fecha_hora=$mytime->ToDateTimeString();
	    		$ingreso->impuesto='16';
	    		$ingreso->estado='A';
	    		$ingreso->save();

	    		$idarticulo=$request->get('idarticulo');
	    		$cantidad=$request->get('cantidad');
	    		$precio_compra=$request->get('precio_compra');
	    		$precio_venta=$request->get('precio_venta');

	    		$cont = 0;

	    		while($cont < count($idarticulo)){
	    			$detalle =  new DetalleIngreso();
	    			$detalle->idingreso = $ingreso->idingreso;
	    			$detalle->idarticulo = $idarticulo[$cont];
	    			$detalle->cantidad = $cantidad[$cont];
	    			$detalle->precio_compra = $precio_compra[$cont];
	    			$detalle->precio_venta = $precio_venta[$cont];
	    			$detalle->save();

	    			$cont=$cont + 1;
	    		}

	    		DB::commit();

	    	}catch(\Exception $e){
	    		DB::rollback();
	    	}

	    	return Redirect::to('compras/ingreso');
	    }/*Comentar Aqui logica y descripccion del Query
        Esta funcion permite guardar nuevo registro de ingreso y el detalle de ingreso para lo cual se emplea una transaccion:
        Se inicia la transaccion y se crea un nuevo ingreso en la variable $ingreso, la cual a traves de los atributos se van almacenando en cada campo desde idproveedor hasta num_comprobante,
        el valor correspondiente del request, luego se utiliza CArbon para registar la fecha, posteriormente se establece el impuesto, el estado y se guarda  atarves de save.
        Tambien se crea un variable $idarticulo que almacena el id de articulo del request asi como las otras variables $cantidad, $precio_compra y $precio_venta, luego se establece un contador
        para recorrer la estructura while mientras ese contador sea menor a la cantidad que se obitene a traves de count($idarticulo) que obtienen el numero de articulos, se crea una variable
        $detalle que almacena un nuevo registro de DetalleIngreso con sus campos correspondientes y se los guarda a traves de save, y se va incrementando el contador.
        Luego se realza un commit o un rollbacj segun el try catch establecido y se redirecciona a la direccion compras/ingreso

      */

	    public function show($id){
	    	$ingreso=DB::table('ingreso as i')
	            ->join('persona as p', 'i.idproveedor', '=', 'p.idpersona')
	            ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
	            ->select('i.idingreso','i.fecha_hora','p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante','i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra)as total'))
	            ->groupBy('i.idingreso','i.fecha_hora','p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante','i.num_comprobante', 'i.impuesto', 'i.estado')
	            ->where('i.idingreso', '=', $id)
	            ->first();

	        $detalles=DB::table('detalle_ingreso as d')
	        ->join('articulo as a', 'd.idarticulo','=','a.idarticulo')
	        ->select('a.nombre as articulo','d.cantidad', 'd.precio_compra', 'd.precio_venta')
	        ->where('d.idingreso','=',$id)->get();

	        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
	    }
      /*Comentar Aqui logica y descripccion del Query
        Esta funcion permite mostrar el priemr ingreso con un id especifico, para ello se crea una variable $ingreso que almacena la consulta que resulta de la union de la tabla ingreso con alias i y la tabla
        persona alias p en el id correspondiente, ademas se une a la tabla detalle_ingreso alias di, para seleccionar campos de la tabla ingreso, persona y se realiza una operacion sum de cantidad por
        precio_compra de la tabla detalle_ingreso cuyo resultado esta con el alias total, agrupando por los msimos campos mostrados en el codigo anteriormente y que ademas ducha consulta debe cumplir con
        que el id de la tabla ingreso sea igual al id introducido en la funcion.
        Ademas se crea una variable $detalle para almacenar la consulta de la tabla de detalle_ingreso alias d, relacionada ala tabla articulo alias a, en su idarticulo, que selecciona el nombre del articulo
        alias articulo, la cantidad, precio de compra y precio de venta de la tabla detalle_ingreso, donde el id de ingreso debe ser igual al id introducido en la funcion.
        Por ultimo muestra la vista compras.ingreso.show
      */

	    public function destroy($id){
	    	$ingreso=Ingreso::findOrFail($id);
	    	$ingreso->estado='C';
	    	$ingreso-update();
	    	return Redirect::to('compras/ingreso');
	    }
      /*Comentar Aqui logica y descripccion del Query
        Esta funcion permite eliminar un registro de ingreso con un id determiando colocanco C en el campo de estado del ingreso corresondiente es decir se realiza una actualizacion del ingreso y se
        redirecicona a la vista compras/ingreso
      */

	}
