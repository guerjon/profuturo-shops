@extends('layouts.master')

@section('content')
<style>
/*  #img-home{
	max-width: 100%;
	max-height: 350px;
	vertical-align:middle;
  }*/
</style>

<div class="conrainer-fluid">
	<div class="row">
		<div class="col-md-4 col-sm-offset-7">
            <div class="login-logos text-center margin-top-150">
                <img src="/img/login-logos.png" alt="">
            </div>
            <div class="panel panel-login">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        Inicia sesión
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open([
                        'action' => 'AuthController@postLogin',
                        'method' => 'POST'
                    ]) !!}
                        <div class="form-group @if($errors->first('cusp')) has-error @endif">
                            {!! Form::label('cusp', 'CUSP') !!}
                            {!! Form::text('cusp', old('cusp'), ['class' => 'form-control', 'required' => 'required','maxlength' =>8]) !!}
                            <small class="text-danger">{{ $errors->first('cusp') }}</small>
                            <p class="help-block alfanum-block"></p>
                        </div>

                        <div class="form-group @if($errors->first('password')) has-error @endif">
                            {!! Form::label('password', 'Contraseña') !!}
                            {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
                            <small class="text-danger">{{ $errors->first('password') }}</small>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">
                                <span class="fa fa-fw fa-sign-in"></span> Iniciar sesión
                            </button>
                        </div>
                    {!! Form::close() !!}
                </div>  
            </div>
        </div>
        <div class="col-sm-8 col-sm-offset-2">

        	@if($errors->login->count() > 0)
        	<div class="alert alert-danger">
        		{{$errors->login->first()}}
        	</div>
        	@endif
        	{{Form::open([
        	'action' => 'AuthController@postLogin',
        	'role' => 'form'
        	])}}
        	<div class="text-center">
        		<div class="row">
        			<div class="col">
        				<img id="img-home" src="/img/home.png">    
        			</div>
        		</div>
        		<div class="row">
        			<div class="col">
        				<img id="img-inside" src="/img/text-login.png">  
        			</div>
        		</div>
        		

        	</div>
        	<div class="form-group">
        		{{Form::label('ccosto', 'Centro de costos o número de empleado:')}}
        		{{Form::text('ccosto', NULL, ['class' => 'form-control'])}}
        	</div>

        	<div class="form-group">
        		{{Form::label('password', 'Contraseña:')}}
        		{{Form::password('password', ['class' => 'form-control'])}}
        	</div>

        	<div class="form-group text-center">
        		{{Form::submit('Enviar', ['class' => 'btn btn-warning'])}}
        	</div>
        	{{Form::close()}}
        </div>
	</div>
</div>
@stop
