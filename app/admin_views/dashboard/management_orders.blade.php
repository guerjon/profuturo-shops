@extends('layouts.master')
	
@section('content')

    <ol class="breadcrumb">
        <a href="{{URL::previous()}}" class="back-btn">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
        &nbsp;&nbsp;&nbsp;
        <li><a href="/">Inicio</a></li>
        <li><a href="{{action('AdminDashboardController@stationery')}}"> Dashboard Papeleria</a></li>
        <li class="active">Gerencias con pedido</li>
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
                            REGION
                        </th>
                        <th>
                            DIVISIONAL
                        </th>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    {{$user->ccosto}}
                                </td>
                                <td>
                                    {{$user->gerencia}}
                                </td>
                                <td>
                                    {{Region::find($user->region_id)->name}}
                                </td>
                                <td>
                                    {{Divisional::find($user->divisional_id)->name}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            
            <div class="text-center">
                {{$users->links()}}
            </div>
            
            <canvas id="furnitures"></canvas>
        </div>
    </div>

@endsection