@extends('layouts.master')
	
@section('content')

    <ol class="breadcrumb">
        <a href="{{URL::previous()}}" class="back-btn">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
        &nbsp;&nbsp;&nbsp;
        <li><a href="/">Inicio</a></li>
        <li><a href="{{action('AdminDashboardController@stationery')}}"> Dashboard Papeleria</a></li>
        <li class="active">Pedidos</li>
    </ol>

    <div class="row">
        <div class="panel panel-primary" style="height:700px">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Pedidos
                </h3>
            </div>
                <table class="table table-striped">
                    <thead>
                        <th>
                            CCOSTOS
                        </th>
                        <th>
                            GERENCIA
                        </th>
                        <th>
                            Número de orden
                        </th>
                        <th>
                            Número de productos
                        </th>
                        <th>
                            Cantidad
                        </th>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    {{$order->ccosto}}
                                </td>
                                <td>
                                    {{$order->gerencia}}
                                </td>
                                <td>
                                    {{$order->id}}
                                </td>
                                <td>
                                    {{$order->quantity}}
                                </td>
                                <td>
                                    {{$order->total}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            
            <div class="text-center">
                {{$orders->links()}}
            </div>
            
            <canvas id="furnitures"></canvas>
        </div>
    </div>

@endsection