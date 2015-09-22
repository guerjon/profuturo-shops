@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
      <a href="#" class="back-btn">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
      </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Divisionales</li>
  </ol>

<h3>
	Divisionales
  <button type="button" style="float:right" class="btn btn-primary" data-toggle="modal" data-target="#add-date">
    <span class="glyphicon glyphicon-plus"></span> Añadir Fecha
  </button>
</h3>

<hr>

	  <a href="#divisional-1" class="btn btn-info" data-toggle="collapse">Divisional 1</a>
  <div id="divisional-1" class="collapse">
		<div class="table table-striped" style="overflow-y">
			
				@if(count($divisionales_uno) > 0)
		
					<h3 style ="float:left;">Divisional 1</h3>
					<table class="table table-striped">
						<thead>
							<th>Desde</th>
							<th>Hasta</th>
							<th></th>
							<th></th>

						</thead>
						<tbody>
							@foreach($divisionales_uno as $divisional)
								<tr>
									<td>
										{{$divisional ? $divisional->DESDE : 'N/A' }}
									</td>
									<td>
										{{$divisional ? $divisional->HASTA : 'N/A' }}
									</td>
									@include('admin::divisionales.partials.actions')
								</tr>
							@endforeach
						</tbody>
					</table>
					@else
					<br>
					<div class="alert alert-info" style="margin:2px">No se han añadido nuevas fechas para esta divisional 1</div>
				@endif
		</div>
  </div>

  <a href="#divisional-2" class="btn btn-info" data-toggle="collapse">Divisional 2</a>
  <div id="divisional-2" class="collapse" style="height:50px;overflow:scroll;">
		<div class="table table-striped" >
			
				@if(count($divisionales_dos) > 0)
					<h3 style ="float:left">Divisional 2</h3>
					<table class="table table-striped">
						<thead>
							<th>Desde</th>
							<th>Hasta</th>
							<th></th>
							<th></th>
						</thead>
						<tbody>
							@foreach($divisionales_dos as $divisional)
								<tr>
									<td>
										{{$divisional ? $divisional->DESDE : 'N/A' }}
									</td>
									<td>
										{{$divisional ? $divisional->HASTA : 'N/A' }}
									</td>
									@include('admin::divisionales.partials.actions')								
								</tr>
							@endforeach
						</tbody>
					</table>
					@else
					<br>
					<div class="alert alert-info" style="margin:2px">No se han añadido nuevas fechas para esta divisional 2</div>
				@endif
		</div>
  </div>

  <a href="#divisional-3" class="btn btn-info" data-toggle="collapse">Divisional 3</a>
  <div id="divisional-3" class="collapse" style="height:50px;overflow:scroll;">
		<div class="table table-striped" >
			
				@if(count($divisionales_tres) > 0)
					<h3 style ="float:left">Divisional 3</h3>
					<table class="table table-striped">
						<thead>
							<th>Desde</th>
							<th>Hasta</th>
							<th></th>
							<th></th>
						</thead>
						<tbody>
							@foreach($divisionales_tres as $divisional)
								<tr>
									<td>
										{{$divisional ? $divisional->DESDE : 'N/A' }}
									</td>
									<td>
										{{$divisional ? $divisional->HASTA : 'N/A' }}
									</td>
									@include('admin::divisionales.partials.actions')								
								</tr>
							@endforeach
						</tbody>
					</table>
					@else
					<br>
					<div class="alert alert-info">No se han añadido nuevas fechas para esta divisional 3</div>
				@endif
		</div>
  </div>


    <a href="#divisional-4" class="btn btn-info" data-toggle="collapse">Divisional 4</a>
  <div id="divisional-4" class="collapse" style="height:50px;overflow:scroll;">
		<div class="table table-striped" >
			
				@if(count($divisionales_cuatro) > 0)
					<h3 style ="float:left">Divisional 4</h3>
					<table class="table table-striped">
						<thead>
							<th>Desde</th>
							<th>Hasta</th>
							<th></th>
							<th></th>
						</thead>
						<tbody>
							@foreach($divisionales_cuatro as $divisional)
								<tr>
									<td>
										{{$divisional ? $divisional->DESDE : 'N/A' }}
									</td>
									<td>
										{{$divisional ? $divisional->HASTA : 'N/A' }}
									</td>
									@include('admin::divisionales.partials.actions')								
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					<br>
					<div class="alert alert-info">No se han añadido nuevas fechas para esta divisional 4</div>
				@endif

		</div>
  </div>

@include('admin::divisionales.partials.create')

@stop

@section('script')
<script>
	$(function(){

		$('#btn-add-date').click(function(){
			$('#form-add-date').submit();
		});

		$('.date-edit').click(function(){

		});

	});
</script>

@stop

