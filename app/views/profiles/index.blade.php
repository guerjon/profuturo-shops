@extends('layouts.master')

@section('content')
	
@if($errors->count() > 0)
<div class="alert alert-danger">
  <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
 </ul>
</div>
@endif


	<center>
		<h1>Mi perfil</h1>	
	</center>
	
<hr>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			{{Form::model($user,['route' => ['perfil.update',$user->id],'method' => 'PUT','files' => 'true'])}}
			<center>
			
			<div class="form-group">
				<div class="media">
	        <div class="media-left">
	          {{HTML::image($user->image->url('medium'), $user->nombre, ['class' => 'img-rounded'])}}
		      </div>
				</div>
			</div>
			<div class='form-group'>
        {{Form::label('image', 'Imagen')}}
        {{Form::file('image')}}
      </div>
				
			</center>
		
			<div class="form-group">
				{{Form::label('nombre','Nombre(s)',['class'=>'control-label','required'])}}
				{{Form::text('nombre',null,['class' => 'form-control'])}}			
			</div>

			<div class="form-group">
				{{Form::label('email','Correo electrónico')}}
			  {{Form::email('email',NULL,['class' => 'form-control','required' => 'true'])}}
	    </div>

			<div class="form-group">
				{{Form::label('celular','Celular',['class'=>'control-label','required' => 'true'])}}
				{{Form::number('celular',null,['class' => 'form-control'])}}			
			</div>

			<div class="form-group">
				{{Form::label('extension','Extensión',['class'=>'control-label','required' => 'true'])}}
				{{Form::text('extension',null,['class' => 'form-control'])}}			
			</div>


			<center>
				<div class="form-group">
				{{Form::submit('Guardar',['class' => 'btn btn-warning'])}}
				</div>	
			</center>
			
			{{Form::close()}}
		</div>	
	</div>

@stop
