@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h3>Editar Usuario: {{ $usuarios->name }}</h3>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors->all() as $error)
				<li>{{ $error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

		{!!Form::model($usuarios,['method'=>'PATCH','route'=>['usuarios.update', $usuarios->id]])!!}

		{{Form::token()}}
<!--
		<div class="form-group">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" class="form-control" value="{{$usuarios->name}}">
		</div>

		<div class="form-group">
			<label for="email">email</label>
			<input type="text" name="email" class="form-control" value="{{$usuarios->email}}">
		</div>
		<div class="form-group">
			<label for="contrasena">contraseña</label>
			<input type="text" name="contrasena" class="form-control" value="{{$usuarios->password}}">
		</div>

		<div class="form-group">
			<button class="btn btn-primary" type="submit">Guardar</button>
			<button class="btn btn-danger" type="reset">Cancelar</button>
		</div>
	-->
	<div class="form-group ">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"value="{{$usuarios->name}}" required autofocus>
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
           @endif
    </div>

    <div class="form-group ">
        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$usuarios->email}}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
    </div>

    <div class="form-group ">
        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
          <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
    </div>

    <div class="form-group ">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
    </div>

    <div class="form-group  mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Guardar') }}
            </button>
						<button type="reset" class="btn btn-danger">
								{{ __('Cancelar') }}
						</button>
        </div>
    </div>
		{!!Form::close()!!}

	</div>
</div>
@endsection
