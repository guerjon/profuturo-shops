@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Pedidos capacitaciones</li>
  </ol>

 

    {{Form::open([
    'method' => 'GET',
    'id' => 'form-training-filter'
    ])}}
    
      <div class="row text-center">
        <div class="col col-xs-2">
          {{Form::label('ccosto','SEDES')}}
          {{Form::select('ccosto', [NULL => 'Todos las sedes'] + Lang::get('sedes'), Input::get('ccosto'), 
          ['id' =>'select-ccostos-bc-orders','class' => 'form-control'])}}
        </div>
        <div class="col col-xs-2">
            {{Form::label('until','DESDE')}}
            {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
        </div>
        <div class="col col-xs-2">
          {{Form::label('to','HASTA')}}
          {{Form::text('to',\Carbon\Carbon::now('America/Mexico_City')->addMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'to' ])}}
        </div>

        <div class="col col-xs-2">
          <br>  
          <button type="submit" class="btn btn-primary" id="filtrar">
            <span class="glyphicon glyphicon-filter"></span> Filtrar
          </button>     
          <button class="btn btn-primary btn-submit" style="float:right">
            <span class="glyphicon glyphicon-download-alt" id="excel"></span> Excel
          </button>
        </div>
      </div>

    {{Form::close()}}
    <hr>
  @if($orders->count() == 0)
    <div class="alert alert-info">
      No se encontraron pedidos aún. 
    </div>
  @else
    <div class="container-fluid">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>
              Gerencia
            </th>
            <th>
              Sedes
            </th>
            <th>
              No. pedido
            </th>
            <th>
              Comentarios
            </th>
            <th>
              Fecha de pedido
            </th>
            <th>
              Estatus
            </th>
            <th>
              Domicilio
            </th>
            <th>
              Orden extra  
            </th>
            <th>
             Acciones
            </th>
          </tr>
        </thead>

        <tbody>
          @foreach($orders as $order)
            <tr>
              <td>
                {{$order->user->gerencia}}
              </td>
              <td>
                @if($order->training_ccosto >= 800000 and $order->training_ccosto <= 800011)
                  {{Lang::get('sedes.'.$order->training_ccosto) }}
                @else
                  {{$order->training_ccosto}}
                @endif
              </td>
              <td>
                {{link_to_action('AdminTrainingOrdersController@show', $order->order_id, [$order->order_id])}}
              </td>
              <td>
                {{$order->comments}}
              </td>
              <td>
                {{$order->training_created_at}}
              </td>
              <td>
                @if($order->status == 0)
                  Pendiente
                @elseif($order->status==1)
                  Recibido <span class="glyphicon glyphicon-check"></span>
                @elseif($order->status==2)
                  Recibido incompleto.
                  @if($complain = $order->order_complain)
                    <small>{{$complain->complain}}</small>
                  @endif
                @endif
              </td>
              <td>                
                @if($order->training_ccosto >= 800000 and $order->training_ccosto <= 800011)
                  {{Lang::get('direcciones_sedes.'.$order->training_ccosto) }}
                @else
                  {{"N/A"}}
                @endif
              </td>
              <td>
                @if($order->extra_order)
                  <div>
                    Orden extra  
                    <span class="glyphicon glyphicon-ok" style="color:green"></span>
                  </div>
                @endif
              </td>
              <td>
                {{Form::open(array('action' =>['AdminTrainingOrdersController@destroy',$order->order_id],
                'method' => 'delete'))}}
                  <button type="submit" class="btn btn-danger btn-xs">
                    <span class="glyphicon glyphicon-remove"></span> Eliminar
                  </button>
                 {{Form::close()}}
              </td>
              </tr>
            @endforeach
          </tbody>
      </table>
    </div>
    <center>
      {{$orders->links()}}
    </center>
  @endif
  @include('admin::address.partials.change_address')

       {{Form::close()}}
@stop

@section('script')
  <script>
    $(function(){
      $('.cambio').click(function(){
          var modal = $('#change-address').modal();

          $('#domicilio').text($(this).attr('data-domicilio'));
          $('#posible_cambio').text($(this).attr('data-posible-cambio'));
          var action = 'training-address/'+ $(this).attr('data-id');
          $('#change-address-form').attr('action',action);          
          modal.show();
      });


      $('.approve').click(function(){
        if($(this).attr('data-value') == 1)
          $('#valor_aprobado').val(1);
        else
          $('#valor_aprobado').val(0);

        $('#change-address-form').submit();
      });

      $('.btn-submit').click(function(){
        $('#form-training-filter').append("<input name='export' value='xls' class='hide' id='hidden-excel'>");
        $('#form-training-filter').submit();
      });
      $('#filtrar').click(function(event){
        event.preventDefault();
        $('#hidden-excel').remove();
        $('#form-training-filter').submit();
      });
    });
  </script>

@endsection
