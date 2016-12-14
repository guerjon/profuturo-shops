@extends('layouts.master')

@section('content')
    <ol class="breadcrumb">
        <a href="{{URL::previous()}}" class="back-btn">
          <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
          &nbsp;&nbsp;&nbsp;
        <li><a href="/">Inicio</a></li>
        <li class="active">Solicitudes Generales</li>
    </ol>
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel panel-heading">
                        <h3>
                            Solicitud general: {{$general->id}}
                        </h3>
                    </div>
                    <dib class="row text-center">
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Nombre: <b> {{$general->employee_name}}    </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Número: <b>{{$general->employee_name}}   </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Email: <b>{{$general->employee_email}} </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Extensión: <b>{{$general->employee_email}} </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Celular: <b>{{$general->employee_cellphone}} </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Título del proyecto: <b>{{$general->project_title}} </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                             Tipo de proyecto:
                            <b>
                                @if($general->kind == 0)
                                    Producto
                                @else
                                    Servicio
                                @endif
                            </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Usuarios finales: <b></b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Lista de distribución
                            @if($general->distribution_list == 0)
                                <b>No</b> 
                            @elseif ($general->distribution_list == 1)
                                <b>Si</b>
                            @else
                                <b>Pendiente</b>
                            @endif
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Estatus: <b>{{$estatus}}</b>
                        </div>    
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Destino: <b>{{$general->project_dest}} </b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Fecha de solicitud: <b>{{$general->created_at->format('d-m-Y')}}</b> 
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Fecha de proyecto: <b>{{$general->project_date->format('d-m-Y')}}</b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Fecha de entrega:<b> {{$general->deliver_date->format('d-m-Y')}}</b>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 well">
                            Comentarios: <b>{{$general->comments}}</b>
                        </div>
                    </dib>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel panel-heading">
                        <h3>
                            Productos
                        </h3>
                    </div>
                    <dib class="row">
                        @foreach(DB::table('general_request_products')->where('general_request_id',$general->id)->get() as $product)
                            <div class="col-xs-6 col-xs-offset-3 well">
                                Nombre: <b> {{$product->name}}</b> <br>
                                Cantidad: <b>{{$product->quantity}} </b><br>
                                Precio : <b>{{$product->unit_price}}</b>
                            </div>
                        </div>
                        @endforeach
                    </dib>
                </div>
            </div>
        </div>
    </div>
  
@stop
<script>
$(function(){

  $('.stars').raty({
      
      score: function() {
        return $(this).attr('data-score');
      },
      scoreName : 'rating',
        path : '/img/raty',
        readOnly: true
  });
 

});

</script>
