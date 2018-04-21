<?php

namespace SisFramework\Http\Controllers;
//session_start();
use Illuminate\Http\Request;
use SisFramework\User;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\input;
//use SisFramework\Http\Request\UserFormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use DB;

class UserController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }



  public function index(Request $request){
if($request){
  $query=trim($request->get('searchText'));
              $users=DB::table('users')
             ->where('name','LIKE','%'.$query.'%')
             ->orderBy('id','desc')
             ->paginate(7);
            return view('acceso/usuarios.index',["usuarios"=>$users,"searchText"=>$query]); // barra o punto
}
}

public function create(){
  return view("acceso.usuarios.create");
}

public function store(Request $request){
  $users = new User;
    $users->name=$request->get('name');
    $users->email=$request->get('email');
    $users->password=Hash::make($request->get('password'));
    $users->remember_token=str_random(10);
    $users->save();
    return Redirect::to('acceso/usuarios');
}

public function show($id){
  return view("acceso.usuarios.show", ["usuarios"=>User::findOrFail($id)]);

}

public function edit($id){
  return view("acceso.usuarios.edit", ["usuarios"=>User::findOrFail($id)]);
}

public function update(Request $request,$id)
{
  $users=User::findOrFail($id);
  $users->name=$request->get('name');
  $users->email=$request->get('email');
  $users->password=Hash::make($request->get('password'));
  $users->remember_token=$request->get('remember_token');
  $users->update();
  return Redirect::to('acceso/usuarios');
}

public function destroy($id){
  $users=User::findOrFail($id);
//  $users->name='Inactivo';
  $users->delete();
  return Redirect::to('acceso/usuarios');


}

}
