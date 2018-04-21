@extends ('layouts.admin')
@section ('contenido')


        	<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
		            	<label for="ingreso">Ingreso</label>
		            	<p>{{$ingreso->nombre}}</p>
	            	</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
		            	<label for="tipo_comprobante">Tipo Comprobante</label>
		            	<p>{{$ingreso->nombre}}</p>
	            	</div>
				</div>


				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
		            	<label for="serie_comprobante">Serie Comprobante</label>
		            	<p>{{$ingreso->serie_comprobante}}</p>
	            	</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
		            	<label for="num_comprobante">Numero Comprobante</label>
		            	<p>{{$ingreso->num_comprobante}}</p>
	            	</div>
				</div>
			</div>
			<div class="row">
				<div class="panel panel-primary">
					<div class="panel-body">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			            	<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
			            		<thead style="background-color:#A9D0F5">
			            			<th>Articulo</th>
			            			<th>Cantidad</th>
			            			<th>Precio Compra</th>
			            			<th>Precio Venta</th>
			            			<th>Subtotal</th>
			            		</thead>
			            			<th></th>
			            			<th></th>
			            			<th></th>
			            			<th></th>
			            			<th><h4 id="total">{{$ingreso->total}}</h4></th>
			            		<tfoot>


			            		</tfoot>

			            		<tbody>
			            			@foreach($detalles as $det)
			            			<tr>
			            				<td>{{$det->articulo}}</td>
			            				<td>{{$det->cantidad}}</td>
			            				<td>{{$det->precio_compra}}</td>
			            				<td>{{$det->precio_venta}}</td>
			            				<td>{{$det->cantidad*$det->precio_compra}}</td>
			            			</tr>
			            			@endforeach
			            		</tbody>
			            	</table>
						</div>

					</div>
				</div>
			</div>

			{!!Form::close()!!}
@endsection
