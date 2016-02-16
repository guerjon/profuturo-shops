@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li class="active">Agregar dirección</li>
</ol>

<h3>Agregar nueva dirección</h3>
<hr>

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">

    {{Form::model($address, [
      'action' => $address->exists ? ['AddressController@update', $address->id] : 'AddressController@store',
      'method' => $address->exists ? 'PUT' : 'POST',
      'files' => true
      ])}}
      <p id = "aviso" >Para agregar una nueva dirección primero debes seleccionar el CCOSTO del usuario</p>
      <div class="form-group">
        {{Form::label('ccostos', 'CCOSTOS')}}
        {{Form::text('ccostos',$address->ccostos, ['class' => 'form-control','id' => 'ccostos','readonly'])}}
      </div>

      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('gerencia', 'GERENCIA:')}} 
        {{Form::text('gerencia',$address->gerencia, ['class' => 'form-control','id' => 'ccostos','readonly'])}}
      </div>


      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('divisional', 'DIVISIONAL:')}} 
        {{Form::text('divisional',$address->divisional, ['class' => 'form-control','id' => 'ccostos','readonly'])}}
      </div>

      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('regional', 'REGIONAL:')}} 
        {{Form::text('regional',$address->regional, ['class' => 'form-control','id' => 'ccostos','readonly'])}}
      </div>

      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('linea_de_negocio', 'LÍNEA DE NEGOCIO:')}} 
        {{Form::text('linea_de_negocio',$address->linea_de_negocio, ['class' => 'form-control','id' => 'ccostos','readonly'])}}
      </div>

       <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('inmueble', 'INMUEBLE')}}
        {{Form::text('inmueble', NULL, ['class' => 'form-control'])}}
      </div>

       <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('domicilio', 'DOMICILIO')}}
        {{Form::textarea('domicilio', NULL, ['class' => 'form-control','rows' => 4])}}
      </div>


      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-warning btn-lg'])}}
      </div>
    {{Form::close()}}
  </div>
</div>

@stop

@section('script')
  <script>
    $('#ccostos').change(function(){
        $('.datos').empty();
        $.get('/api/user/',{ccostos : $(this).val()}, function(data){
          if(data.status == 200){
            
            $('#gerencia').val(data.user.gerencia);

            if(data.user.divisional_id == 1)
              $('#divisional').val("DIRECCION MERCADOTECNIA Y VALOR AL CLIENTE");  
            if(data.user.divisional_id == 2)
              $('#divisional').val("DIRECCION DIVISIONAL SUR");  
            if(data.user.divisional_id == 3)
              $('#divisional').val("DIRECCION DIVISIONAL NORTE");  
            if(data.user.divisional_id == 4)
              $('#divisional').val("DIRECCION REGIONAL DE NEGOCIOS DE GOBIERNO Y PENSIONES");  

            $('#regional').val(data.user.region.name);
            $('#linea_de_negocio').val(data.user.linea_negocio);

          }else{
            $('#aviso').empty();
            $('#aviso').append('<div class="alert alert-danger">El centro de costos no esta registrado.</div>')
          }
        }); 
    });
  </script>
@endsection