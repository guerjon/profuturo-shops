@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="#" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="#">Inicio</a></li>
    <li><a href="#">Usuarios</a></li>
    <li class="active">Añadir Usuario</li>
  </ol>


<div class="row">

  <div class="col-md-8 col-md-offset-2">

    @if($user->getErrors()->count() > 0)
      <div class="alert alert-danger">
        @if($user->getErrors()->count() == 1)
          {{$user->getErrors()->first()}}
        @else
          Ocurrieron errores al guardar el nuevo usuario:
          <ul>
            @foreach($user->getErrors()->all() as $error)
            <li>{{$error}}</li>
            @endforeach
          </ul>
        @endif
      </div>
    @endif
    {{Form::model($user, [
      'action' => $user->exists ? ['AdminUsersController@update', $user->id] : 'AdminUsersController@store',
      'method' => $user->exists ? 'PUT' : 'POST',
      'id'     => 'user-create',
      ])}}
      <h3>Dar de alta un nuevo Usuario</h3>
      <div class="form-group">
        {{Form::label('ccosto', 'Centro de costos', ['class' => 'control-label'])}}
        <div>
          @if($user->exists)
          <p class="form-control-static">
            {{$user->ccosto}}
          </p>
          @else
          {{Form::number('ccosto', NULL, ['class' => 'form-control'])}}
          @endif
        </div>
      </div>
      <div class="form-group">
        {{Form::label('email','Correo electrónico',['class' => 'control-label'])}}
        <div>
          {{Form::email('email',null,['class' => 'form-control'])}}
        </div>
      </div>

      <div class="form-group">
        {{Form::label('divisional','Divisional',['class' => 'control-label'])}}
        <div>
          {{Form::select('divisional',[1 => '1',2 => '2',3 => '3',4 => '4'],null,['class' => 'form-control'])}}
        </div>
      </div>     

      <div class="form-group">
        {{Form::label('region_id','Region',['class' => 'control-label'])}}
        <div>
          {{Form::select('region_id',$regions,null,['class' => 'form-control'])}}
        </div>
      </div>   

      <div class="form-group">
        {{Form::label('gerencia', 'Nombre/Gerencia', ['class' => 'control-label'])}}
        <div>
          {{Form::text('gerencia', NULL, ['class' => 'form-control'])}}
        </div>
      </div>

      <div class="form-group">
        {{Form::label('linea_negocio', 'Línea de negocio', ['class' => 'control-label'])}}
        <div>
          {{Form::select('linea_negocio',['FONDOS' => 'FONDOS','AFORE' => 'AFORE','PENSIONES' => 'PENSIONES','PRESTAMOS' => 'PRESTAMOS'],'FONDOS',['class' => 'form-control'])}}
        </div>
      </div>

      @unless($user->exists)
      <div class="form-group">
        {{Form::label('password', 'Contraseña', ['class' => 'control-label'])}}
        <div>
          {{Form::password('password', ['class' => 'form-control'])}}
        </div>
      </div>

      <div class="form-group">
        {{Form::label('password_confirmation', 'Confirma contraseña', ['class' => 'control-label'])}}
        <div>
          {{Form::password('password_confirmation', ['class' => 'form-control'])}}
        </div>
      </div>

      @endunless


      <div class="form-group">
        {{Form::label('role', 'Perfil', ['class' => 'control-label'])}}
        <div>
          @if($user->exists)
            <p class="form-control-static">
              @if($user->role == 'admin')
              Administrador
              @elseif($user->role == 'manager')
              Consultor
              @elseif($user->role == 'user_requests')
              Usuario proyectos
              @else
              Usuario papelería
              @endif
            </p>
          @else
            <label class="radio-inline">{{Form::radio('role', 'admin')}} Administrador</label>
            <label  class="radio-inline">{{Form::radio('role', 'manager',null,['id'=>'consultor'])}} Consultor</label>
            <br>
            <label class="radio-inline">{{Form::radio('role', 'user_requests',null,['id'=>'usuario-proyectos'])}} Usuario proyectos</label>
            <label  class="radio-inline">{{Form::radio('role', 'user_paper')}} Usuario papelería</label>
          @endif
        </div>
      </div>

      <center>
        <div id="campos-extra">
          <div class="form-group">
            {{Form::label('nombre', 'Nombre de Usuario de Solicitudes', ['class' => 'control-label col-sm-4'])}}
            <div class="col-sm-8">
              {{Form::text('nombre', NULL, ['class' => 'form-control'])}}
            </div>
          </div>
          <div class="form-group">
            {{Form::label('num_empleado', 'Número de empleado', ['class' => 'control-label col-sm-4'])}}
            <div class="col-sm-8">
              {{Form::number('num_empleado', NULL, ['class' => 'form-control'])}}
            </div>
          </div>
        
          <div class="form-group">
            {{Form::label('extension', 'Extensión', ['class' => 'control-label col-sm-4'])}}
            <div class="col-sm-8">
              {{Form::text('extension', NULL, ['class' => 'form-control'])}}
            </div>
          </div>
          <div class="form-group">
            {{Form::label('celular', 'Celular', ['class' => 'control-label col-sm-4'])}}
            <div class="col-sm-8">
              {{Form::text('celular', NULL, ['class' => 'form-control'])}}
            </div>
          </div>
        </div>
      </center>
      <center>
        <div  id = "colores"  class="form-group">
        <label class="radio-inline">
          Color del consultor
        </label>
            @if(isset($colors))
              @foreach($colors as $color)
                <label style="background-color: {{$color->color}}; width:30%" class="radio">
                {{Form::radio('color_id', $color->id)}}  {{$color->color}}
                </label>
              @endforeach  
            @endif       
        </div>
      </center>

      <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
          {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
        </div>
      </div>
    {{Form::close()}}
  </div>
</div>
@stop

@section('script')
  <script type="text/javascript" >
    $(function(){
      $('#colores').hide();
      $('#campos-extra').hide();
      $('.radio-inline input[type="radio"]').click(function(){
        if($(this).attr('id') == 'consultor'){
          $('#colores').show();
        }else{
          $('#colores').hide();
        }
        if(($(this).attr('id')=='usuario-proyectos') || ($(this).attr('id')=='consultor')){
          $('#campos-extra').show();
        }else{
          $('#campos-extra').hide();
        }
      });
      $.validator.setDefaults({
        focusCleanup: true
      });
      $( "#user-create" ).validate({
        rules: {
          password: "required",
          password_confirmation: {
            equalTo: "#password"
          }
        },
        messages:{
          email: { email:"Por favor introduce un correo válido",
                   required:"El email es requerido"
          },
          password:{
            required:'La contaseña es requerida'
          },
          password_confirmation: "Las contraseñas deben de ser iguales"
        }
      });
    });    

  </script>
@stop
