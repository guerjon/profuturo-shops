@extends('layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1>Confirmación de orden</h1>
        </div>
        <hr>
        {{ Form::open([
            'action' => ['BcOrdersController@update', $bc_order->id],
            'method' => 'PUT',
            'id'     => 'form',
        ])}}
            
                @foreach($talentos as $talento)
                    <input type="hidden" class="hidden" name="talentos[]" value="{{$talento}}">  
                @endforeach
  
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Dirección
                        </th>
                        <th>
                            Dirección alternativa
                        </th>
                        <th>
                            Teléfono
                        </th>
                        <th>
                            Celular
                        </th>
                        <th>
                            Correo electrónico
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($bc_order->business_cards) > 0)
                        @foreach($bc_order->business_cards as $card)
                            <tr>
                                <td>
                                    {{Form::text("card[{$card->id}][nombre]", $card->nombre, ['class' => 'form-control'])}}
                                </td>
                                <td>
                                    {{$card->direccion_alternativa}}
                                </td>
                                <td>
                                    {{Form::text("card[{$card->id}][direccion_alternativa]", $card->direccion_alternativa,['class' => 'form-control'])}}
                                </td>
                                <td>
                                    {{Form::text("card[{$card->id}][telefono]", $card->telefono, ['class' => 'form-control phone','data-name' => $card->nombre])}}
                                </td>
                                <td>
                                  {{Form::text("card[{$card->id}][celular]", $card->celular, ['class' => 'form-control cellphone','data-name' => $card->nombre])}}
                                </td>
                                <td>
                                   {{Form::text("card[{$card->id}][email]", $card->email, ['class' => 'form-control email'])}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if($talent == "1")

                        <tr>    
                            <td>
                               {{Form::text("talento_nombre", NULL, ['class' => 'form-control','placeholder' => 'Atracción de talento'])}}
                            </td>
                            <td>
                              {{Form::text("talento_direccion", NULL, ['class' => 'form-control'])}}
                            </td>
                            <td>
                              {{Form::text("talento_direccion_alternativa", NULL, ['class' => 'form-control'])}}
                            </td>
                            </td>
                            <td>
                              {{Form::text("talento_tel", NULL, ['class' => 'form-control phone','onchange'=>"$('#save').prop('disabled',false)",
                              'onkeypress'=>"$('#save').prop('disabled',false)"])}}
                            </td>
                            <td>
                              {{Form::text("talento_cel", NULL, ['class' => 'form-control phone'])}}
                            </td>
                            <td>
                              {{Form::email("talento_email", NULL, ['class' => 'form-control'])}}
                            </td>

                        </tr>

                    @endif
                    @if($manager == "1")
                        <tr>
                            <td>
                               {{Form::text("gerente_nombre", NULL, ['class' => 'form-control','placeholder' => 'Gerente comercial'])}}
                            </td>
                            <td>
                              {{Form::text("gerente_direccion", NULL, ['class' => 'form-control'])}}
                            </td>
                            <td>
                              {{Form::text("gerente_direccion_alternativa", NULL, ['class' => 'form-control'])}}
                            </td>
                            <td>
                              {{Form::text("gerente_tel", NULL, ['class' => 'form-control phone','onchange'=>"$('#save').prop('disabled',false)",
                              'onkeypress'=>"$('#save').prop('disabled',false)"])}}
                            </td>
                            <td>
                              {{Form::text("gerente_cel", NULL, ['class' => 'form-control phone'])}}
                            </td>
                            <td>
                              {{Form::email("gerente_email", NULL, ['class' => 'form-control'])}}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="form-group">
                {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 3])}}
            </div>

            @if($remaining_cards)
                <div class="form-group">

                    {{ Form::label('blank_cards', '¿Desea añadir tarjetas blancas a su pedido? Recuerde que solo puede pedir 200 cada mes')}}
                    @if($remaining_cards > 100)
                        <div class="row">
                          <div class="col-xs-2">
                          {{Form::select('blank_cards', [0,100,200], NULL, ['class' => 'form-control'])}}
                          </div>
                         
                          <div class="col-xs-2">
                          {{Form::select('nombre_puesto',array(
                                                              'Asesor en Retiro' => 'Asesor en Retiro',
                                                              'Asesor Previsional' =>'Asesor Previsional',
                                                              'Ejecutivo de Cuenta'=>'Ejecutivo de cuenta',
                                                              ' Ejecutivo de aportaciones voluntarias' => 'Ejecutivo de aportaciones voluntarias',
                                                              'Gerente de aportaciones voluntarias' => 'Gerente de aportaciones voluntarias'
                                                              ), NULL, ['class' => 'form-control'])}}
                          </div>
                          <div class="col-xs-4">
                            {{Form::text('direccion_alternativa_tarjetas',NULL, ['class' => 'form-control','placeholder' => 'Dirección alternativa'])}}
                          </div>
                          <div class="col-xs-2">
                            {{Form::text('telefono_tarjetas',NULL, ['class' => 'form-control','placeholder' => 'Telefono'])}}
                          </div>
                          <div class="col-xs-2">
                            {{Form::text('email',NULL, ['class' => 'form-control','placeholder' => 'Correo electronico'])}}
                          </div>
                        </div>
                    @endif
                </div>

            @else 
                Las tarjetas blancas no estan disponibles ya que se llego limite de 100. 
            @endif
            <center>
                <button type="button" id="cancel-order-button" class="btn btn-danger">Cancelar</button>
                <button class="btn btn-default" id="save" type="button">
                  <span class="fa fa-save"></span>
                  Guardar
                </button>
            </center>              
        {{Form::close()}}
    </div>

    




@include('bc_orders.modals.warning')

@stop


@section('script')
  <script src="/js/jquery.maskedinput.min.js"></script>

  <script>

    $(function(){
        //

        $('input.phone').mask('9999 9999', {placeholder: '####-####'});
        $('input.cellphone').mask('99 9999 9999', {placeholder : '##-####-####'});

        var errors_text = [,'El número de telefono es requerido.'];


        $('#save').click(function(){
            var phone_errors = [];
            var same_errors = [];

            $('.phone').each(function(){
                
                var val = $(this).val();
                var name = $(this).attr('data-name');

                if (val.length <= 0) {   
                    phone_errors.push(name);
                }

                if(val == '5555 5555'){
                    same_errors.push(name)
                }

            });

            if(phone_errors.length > 0 || same_errors.length > 0){

                $('#warning-modal').find('#warning-data').empty();

                $.each(phone_errors,function(k,name){
                    $('#warning-modal').find('#warning-data').append('<p style="color:red">El telefono de la tarjeta de <strong>' + name + '</strong> es incorrecto. </p><br>');
                });

                $.each(same_errors,function(k,name){
                    $('#warning-modal').find('#warning-data').append('<p style="color:red">El número 5555 5555 de la tarjeta de <strong>' + name + '</strong> no esta permitido. </p><br>');
                });
                
                $('#warning-modal').modal();
            }else{
                $('#form').submit();
            }
        });
    });
  </script>

@stop
