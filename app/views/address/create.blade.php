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
      <p>Si el usuario ya tiene asignada la dirección esta es editable.</p>
      <div class="form-group">
        {{Form::label('ccostos', 'CCOSTOS')}}
        {{Form::text('ccostos',NULL, ['class' => 'form-control','id' => 'ccostos','required','readonly'])}}
      </div>

      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('gerencia', 'GERENCIA:')}} <input id="gerencia" name="gerencia" readonly  class="datos form-control">
      </div>


      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('divisional', 'DIVISIONAL:')}} <input id="divisional" name="divisional" readonly class="datos form-control">
      </div>

      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('regional', 'REGIONAL:')}} <input id="regional" name="regional" readonly class="datos form-control">
      </div>

      <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('linea_de_negocio', 'LÍNEA DE NEGOCIO:')}} <input name="linea_de_negocio" id="linea_de_negocio" readonly class="datos form-control">
      </div>

       <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('inmueble', 'INMUEBLE')}}
        {{Form::text('inmueble', NULL, ['class' => 'form-control','required'])}}
      </div>

       <div class="form-group" style="background-color:#FCFAFA;">
        {{Form::label('domicilio', 'DOMICILIO')}}
        {{Form::textarea('domicilio', NULL, ['class' => 'form-control','rows' => 4,'required'])}}
      </div>

      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-warning btn-lg','id' => 'boton'])}}
      </div>
    {{Form::close()}}
  </div>
  
</div>

@stop

@section('script')
  <script>
    $(function(){
        $('#ccostos').attr('readonly',false)
          $.ajax({
            url : '/api/ccostos-autocomplete',
            dataType: 'json',
            success : function(data){
              console.log(data);
              if(data.status == 200){
console.log(data);
                var orders = data.orders;
                var ccostos = data.ccostos;

                $('#ccostos').autocomplete(
                  {
                    source:ccostos,
                    minLength: 1,
                    select: function(event,ui){
                      llena_inputs(ui)
                    },
                  }
                );
                
              }
            },error : function(data){
              }
          });
    });

    function llena_inputs(ui){

        $('.datos').empty();
        $.get('/api/user-direcction/',{ccostos : ui.item.value }, function(data){
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

            $('#regional').val(data.user.regional);
            $('#linea_de_negocio').val(data.user.linea_negocio);
            $('#inmueble').val(data.user.inmueble);
            $('#domicilio').val(data.user.domicilio);

            console.log(data);

          }else{
            $('#aviso').empty();
            $('#aviso').append('<div class="alert alert-danger">El centro de costos no esta registrado.</div>')
          }
        }); 
    }

  </script>
@endsection