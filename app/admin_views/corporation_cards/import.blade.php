@extends('layouts.master')

@section('content')

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
