@extends('layouts.master')

@section('content')

<div class="row">
	<ol class="breadcrumb">
	  	<a href="{{URL::previous()}}" class="back-btn">
	    	<span class="glyphicon glyphicon-arrow-left"></span> Regresar
	  	</a>
	    	&nbsp;&nbsp;&nbsp;
		<li><a href="/">Inicio</a></li>
		<li><a href="/admin/corporation-uploads">Tarjetas de Corporativo</a></li>
		<li><a href="corporation-uploads">Cargas tarjetas corporativo</a></li>
		<li class="active">Importar Excel</li>
	</ol>
</div>

	<div class="row text-center">
		<h1>Importar tarjetas de corporativo</h1>
		<div class="form" style="margin-top:5%">
			{{Form::open(['action' => 'AdminCorporationCardsController@store','files'=>'true'])}}
				<div class="col-xs-4 col-xs-offset-4">
					<center>
						{{Form::label('file', 'Archivo Excel')}}
						<input type="file" name="file" required class="btn btn-default">
						<br>
						<br>
						<button class="btn btn-primary btn-lg" type="submit">
							Guardar
							<span class="glyphicon glyphicon-floppy-disk"></span>
						</button>
					</center>
				</div>
			{{Form::close()}}
		
		</div>
	</div>

@endsection
