@extends('layouts.master', ['bodyClass' => 'bg-login'])

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
                    {{Form::open([
                    'action' => 'AuthController@postLogin',
                    'role' => 'form'
                    ])}}
                        <div class="form-group @if($errors->first('cusp')) has-error @endif">
                            {{Form::label('ccosto', 'Centro de costos o número de empleado:')}}
                            {{Form::text('ccosto', NULL, ['class' => 'form-control'])}}
                        </div>

                        <div class="form-group @if($errors->first('password')) has-error @endif">
                            {{Form::label('password', 'Contraseña:')}}
                            {{Form::password('password', ['class' => 'form-control'])}}
                        </div>
                        
                        <div class="form-group text-center">
                            {{Form::submit('Iniciar sesión', ['class' => 'btn btn-primary'])}}
                        </div>
                    {{Form::close()}}
                </div>  
            </div>
        </div>
	</div>
</div>
@stop
