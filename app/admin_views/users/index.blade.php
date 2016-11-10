@extends('layouts.master')

@section('content')
	<div class="container-fluid">
			
		<div class="row">
			<ol class="breadcrumb">
				<a href="{{URL::previous()}}" class="back-btn">
					<span class="glyphicon glyphicon-arrow-left"></span> Regresar
				</a>
					&nbsp;&nbsp;&nbsp;
				<li><a href="/">Inicio</a></li>
				<li class="active">Usuarios</li>
			</ol>		
		</div>

		<div class="row">
			@if($errors->count() > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif		
		</div>
	
		<div class="row">
			<center>
				<h1>
					Usuarios
				</h1>				
			</center>
		</div>

		<div class="row">
			<div class="col-xs-11">
				{{ Form::open([
					'method' => 'GET',
					'class' => 'form-horizontal'
					]) }}
					<input type="hidden" name="active_tab" value="{{$active_tab}}">
					<div class="form-group">
						<div class="col-xs-3">
							{{Form::text('ccosto',Input::get('ccosto'), ['placeholder' => 'CCOSTOS','class' => 'form-control'])}}
						</div>
						<div class="col-xs-3">
							{{Form::text('num_empleado',Input::get('num_empleado'), ['placeholder' => 'Número de Empleado','class' => 'form-control'])}}
						</div>
						<div class="col-xs-3">
							{{Form::text('gerencia',Input::get('gerencia'),['placeholder' => 'Gerencia','class' => 'form-control'])}}
						</div>

						<div class="col-xs-2">
							<button type="submit" class="btn btn-block btn-default">
								<span class="glyphicon glyphicon-search"></span> 
							</button>
						</div>
					</div>
				{{ Form::close() }}

			</div>
			<div class="col-xs-1">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    				Acciones <span class="caret"></span>
  				</button>
				<ul class="dropdown-menu">
					<li>
						<a href="{{action('AdminUsersController@getImport')}}">
							<span class="glyphicon glyphicon-import"></span> Importar Excel
						</a>										
					</li>
					<li>
						<a href="{{action('AdminApiController@getUsersReport')}}">
							<span class="glyphicon glyphicon-download-alt"></span> Descargar
						</a>											
					</li>
					<li>
						<a href="{{action('AdminUsersController@create',['active_tab'=>$active_tab])}}">
							<span class="glyphicon glyphicon-plus"></span> Usuario
						</a>															
					</li>
				</ul>
			</div>

		</div>

		<div class="row">
			<div class="" style="margin: 20px inherit">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="{{$active_tab == 'admin' ? 'active' : ''}}">
						<a href="?active_tab=admin&page=1" aria-controls="admin" class="tabs">
							Administradores
						</a>
					</li>
					<li role="presentation" class="{{$active_tab == 'manager' ? 'active' : ''}}">
						<a href="?active_tab=manager&page=1" aria-controls="manager" class="tabs">
							Consultores
						</a>
					</li>
					<li role="presentation" class="{{$active_tab == 'user_paper' ? 'active' : ''}}">
						<a href="?active_tab=user_paper&page=1" aria-controls="user_paper" class="tabs">
							Papelería
						</a>
					</li>  
					<li role="presentation" class="{{$active_tab == 'user_requests' ? 'active' : ''}}">
						<a href="?active_tab=user_requests&page=1" aria-controls="user_requests" class="tabs">
							Proyectos
						</a>
					</li>   
					<li role="presentation" class="{{$active_tab == 'user_furnitures' ? 'active' : ''}}">
						<a href="?active_tab=user_furnitures&page=1" aria-controls="user_furnitures" class="tabs">
							Inmuebles
						</a>
					</li>
					<li role="presentation" class="{{$active_tab == 'user_loader' ? 'active' : ''}}">
						<a href="?active_tab=user_loader&page=1" aria-controls="user_loader" class="tabs">
							Cargador sucursal
						</a>
					</li>
					<li role="presentation" class="{{$active_tab == 'user_mac' ? 'active' : ''}}">
						<a href="?active_tab=user_mac&page=1" aria-controls="user_mac" class="tabs">
							MAC
						</a>
					</li>    
					<li role="presentation" class="{{$active_tab == 'user_corporation' ? 'active' : ''}}">
						<a href="?active_tab=user_corporation&page=1" aria-controls="user_corporation" class="tabs">
							Corporativo
						</a>
					</li>    
					<li role="presentation" class="{{$active_tab == 'user_training' ? 'active' : ''}}">
						<a href="?active_tab=user_training&page=1" aria-controls="user_training" class="tabs">
							Capacitadores
						</a>
					</li>    
					<li role="presentation" class="{{$active_tab == 'user_system' ? 'active' : ''}}">
						<a href="?active_tab=user_system&page=1" aria-controls="user_system" class="tabs">
							Sistema
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			@if($users->count() > 0)
				@include('admin::users.partials.table')
			@else
				<div class="alert alert-info">
					No se encontraron resultados
				</div>
			@endif
		</div>
	
		@include('admin::address.partials.change_address')
		

	</div>
@stop
@section('script')
	<script>
		$(function(){
			$('#cambio').click(function(){
					var modal = $('#change-address').modal();

					$('#domicilio').text($(this).attr('data-domicilio'));
					$('#posible_cambio').text($(this).attr('data-posible-cambio'));
					var action = 'address/'+ $(this).attr('data-id');
					$('#change-address-form').attr('action',action);          
					modal.show();
			});


			$('.approve').click(function(){
				console.log($(this).attr('data-value'));
				if($(this).attr('data-value') == 1)
					$('#valor_aprobado').val(1);
				else
					$('#valor_aprobado').val(0);

				$('#change-address-form').submit();
			});
		});
	</script>
@endsection