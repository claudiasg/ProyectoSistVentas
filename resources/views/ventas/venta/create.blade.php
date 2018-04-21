@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nueva Venta</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
		</div>
	</div>

			{!!Form::open(array('url'=>'ventas/venta','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
        	<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">	
					<div class="form-group">
		            	<label for="proveedor">Cliente</label>
		            	<select name="idcliente" class="form-control selectpicker" data-live-search="true">
			            	@foreach($personas as $persona)
			            	<option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>	
			            	@endforeach
			            </select>
	            	</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">	
					<div class="form-group">
		            	<label for="tipo_comprobante">Tipo Comprobante</label>
		            	<select name="tipo_comprobante" class="form-control">
			            	
			            	<option value="Boleta">Boleta</option>
			            	<option value="Factura">Factura</option>
			            	<option value="Ticket">Ticket</option>	
			            	
			            </select>
	            	</div>
				</div>


				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
		            	<label for="serie_comprobante">Serie Comprobante</label>
		            	<input type="text" name="serie_comprobante"  value="{{old('serie_comprobante')}}" class="form-control" placeholder="Serie Comprobante...">
	            	</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
		            	<label for="num_comprobante">Numero Comprobante</label>
		            	<input type="text" required name="num_comprobante"  value="{{old('num_comprobante')}}" class="form-control" placeholder="Numero Comprobante...">
	            	</div>
				</div>
			</div>
			<div class="row">
				<div class="panel panel-primary">
					<div class="panel-body">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">	
							<div class="form-group">
				            	<label>Articulo</label>
				            	<select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
					            	@foreach($articulos as $articulo)
					            	<option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{$articulo->precio_promedio}}">{{$articulo->articulo}}</option>	
					            	@endforeach
					            </select>
			            	</div>
			            </div>
			            
			            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			            	<div class="form-group">
				            	<label for="cantidad">Cantidad</label>
		            			<input type="number"  name="pcantidad" id="pcantidad"   class="form-control" placeholder="Cantidad...">
			            	</div>
						</div>

						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			            	<div class="form-group">
				            	<label for="stock">Stock</label>
		            			<input type="number"  disabled name="pstock" id="pstock"   class="form-control" placeholder="Cantidad...">
			            	</div>
						</div>

						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			            	<div class="form-group">
				            	<label for="precio_venta">Precio Venta</label>
		            			<input type="number" disabled name="pprecio_venta" id="pprecio_venta"   class="form-control" placeholder="P. Compra...">
			            	</div>
						</div>

						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			            	<div class="form-group">
				            	<label for="pdescuento">Descuento</label>
		            			<input type="number"  name="pdescuento" id="pdescuento"   class="form-control" placeholder="P. Compra...">
			            	</div>
						</div>

						

						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			            	<div class="form-group">
				            	<center><button type="button" id="bt_add" class="btn btn-primary">Agregar</button></center>
			            	</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			            	<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
			            		<thead style="background-color:#A9D0F5">
			            			<th>Opciones</th>
			            			<th>Articulo</th>
			            			<th>Cantidad</th>
			            			<th>Precio Venta</th>
			            			<th>Descuento</th>
			            			<th>Subtotal</th>
			            		</thead>
			            			<th>TOTAL</th>
			            			<th></th>
			            			<th></th>
			            			<th></th>
			            			<th></th>
			            			<th><h4 id="total">MNX/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>
			            		<tfoot>

			            			
			            		</tfoot>

			            		<tbody>
			            			
			            		</tbody>
			            	</table>
						</div>

					</div>
				</div>
				


				 <div class="form-group">

            		<center>
            			<input name="token" value="{{csrf_token()}}" type="hidden"></input>
            			<button class="btn btn-primary" type="submit">Guardar</button>
            			<button class="btn btn-danger" type="reset">Cancelar</button>
            		</center>
            	</div>
				
			</div>

			{!!Form::close()!!}		

		@push('scripts')
		<script>
			$(document).ready(function(){//comentar el Script
				$('#bt_add').click(function(){
					agregar();
				});
				
			});

			var cont=0;
			total=0;
			subtotal=[];
			$("#guardar").hide();
			$("#pidarticulo").change(mostrarValores);

			function mostrarValores(){
				datosArticulo=document.getElementById('pidarticulo').value.split('_');
				$("#pprecio_venta").val(datosArticulo[2]);
				$("#pstock").val(datosArticulo[1]);
			}

			function agregar(){
				var datosArticulo=document.getElementById("pidarticulo").value.split('_');

				var idarticulo = datosArticulo[0];
				var articulo=$('#pidarticulo option:selected').text();
				var cantidad = document.getElementById("pcantidad").value;
				var descuento = document.getElementById("pdescuento").value;
				if(descuento == ""){
					descuento = 0;
				}
				var precio_venta = document.getElementById("pprecio_venta").value;
				var stock = document.getElementById("pstock").value;

				if(idarticulo!="" && cantidad!="" && cantidad>0 && precio_venta!=""){

						subtotal[cont]=(cantidad*precio_venta-descuento);
						total=total+subtotal[cont];
						var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+')">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td><td><input type="number" name="descuento[]" value="'+descuento+'"></td><td>'+subtotal[cont]+'</td></tr>';
						cont++;

						limpiar();
						$("#total").html("MXN/ " + total);
						$("#total_venta").val(total);
						evaluar();
						$('#detalles').append(fila);	
					
					
				}else{
					alert("Error al ingresar el detalle de la venta, revise los datos del venta");
				}

			}

			function limpiar(){
				$("#pcantidad").val("");
				$("#pdescuento").val("");
				$("#pprecio_venta").val("");	
			}

			function evaluar(){
				if(total>0){
					$("#guardar").show();
				}else{
					$("#guardar").hide();
				}
			}

			function eliminar(index){
				total=total-subtotal[index];
				$("#total").html("MXN/ " + total);
				$("#total_venta").val(total);
				$("#fila"+index).remove();
				evaluar();
			}

		</script>
		@endpush

@endsection