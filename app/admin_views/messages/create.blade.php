@extends('layouts.master')

@section('content')
		
	<div class="container">
		<div class="row">
			<h3>Crear Nuevo mensaje</h3>
			<hr>
		</div>
		<div class="row">
			
	    	<ul class="nav nav-tabs" role="tablist">
		      	<li role="presentation" class="{{$active_tab == 'users' ? 'active' : ''}}">
		        	<a href="?active_tab=users&page=1" class="tabs">
		          		Usuarios
		        	</a>
		      	</li>			
		      	<li role="presentation" class="{{$active_tab == 'regions' ? 'active' : ''}}">
		        	<a href="?active_tab=regions&page=1" class="tabs">
		          		Regiones
		        	</a>
		      	</li>			
		      	<li role="presentation" class="{{$active_tab == 'divisionals' ? 'active' : ''}}">
		      		<a href="?active_tab=divisionals&page=1" class="tabs">
		      			Divisionales
		      		</a>
		      	</li>
		    </ul>							
			
		</div>
		{{Form::open(
			[
				'action' => 'AdminMessagesController@store',
				'method' => 'post'
			]
		)}}
			@if($active_tab == 'users')
				<div class="row">
					{{Form::label('receivers','Enviar mensaje a:',['class' => 'label-control'])}}
					{{Form::select('receivers[]',User::lists('gerencia','id'),null,['class' => 'select2','id' => 'users_select','multiple','style' => 'width:100%','required'])}}	
				</div>
			@elseif($active_tab == 'regions')
				<div class="row">
					{{Form::label('receivers','Enviar mensaje a',['class' => 'label-control'])}}
					{{Form::select('receivers[]',Region::lists('name','id'),null,['class' => 'select2','id' => 'users_select','multiple','style' => 'width:100%','required'])}}	
				</div>
			@else
				<div class="row">
					{{Form::label('receivers','Enviar mensaje a',['class' => 'label-control'])}}
					{{Form::select('receivers[]',Divisional::lists('name','id'),null,['class' => 'select2','id' => 'users_select','multiple','style' => 'width:100%','required'])}}	
				</div>
			@endif
			<br>
			<div class="row">
				{{Form::label('body','Mensaje',['class' => 'label-control'])}}
				{{Form::textArea('body',null,['class' => 'form-control','required'])}}
			</div>
			<input type="hidden" name="active_tab" value="{{$active_tab}}">
			<br>
			<div class="row text-center">
				<button type="submit" class="btn btn-primary btn-lg">
					Enviar
				</button>
			</div>

		{{Form::close()}}
	</div>

@endsection

@section('script')
	<script>
		$(function(){
			$('.select2').select2({theme:'bootstrap'});
		});

	</script>
@endsection
@section('style')
	
@endsection