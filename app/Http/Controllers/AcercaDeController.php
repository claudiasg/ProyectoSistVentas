<?php

namespace SisFramework\Http\Controllers;

use SisFramework\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SisFramework\Http\Requests\CategoriaFormRequest;
use DB;

class AcercaDeController extends Controller
{
  public function __construct() {
      $this->middleware('auth');
  }

  public function index(Request $request) {

      return view('acercaDe.index');
  }


}
