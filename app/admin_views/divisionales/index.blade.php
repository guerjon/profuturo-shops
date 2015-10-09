@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
      <a href="#" class="back-btn">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
      </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Divisionales</li>
  </ol>

<h3>
	Divisionales
  <button type="button" style="float:right" class="btn btn-primary" data-toggle="modal" data-target="#add-date">
    <span class="glyphicon glyphicon-plus"></span> Añadir Fecha
  </button>
</h3>

  <div class="" style="margin: 20px inherit">
     <ul class="nav nav-tabs" role="tablist">
      @foreach($divisionals as $divisional)
        <li role="presentation" class="{{$active_tab == $divisional->id ? 'active' : ''}}">
          <a href="?active_tab={{$divisional->id}}&page=1" aria-controls="{{$divisional->name}}" class="tabs">
            {{$divisional->name}}
          </a>
        </li>
      @endforeach
    </ul>
  </div>

	<div class="table table-striped">
			@if($divisionals_date)
				<table class="table table-striped">
					<thead>
						<th>Desde</th>
						<th>Hasta</th>
						<th></th>
						

					</thead>
					<tbody>
						@foreach($divisionals_date as $divisional)
							<tr>
	 							<td>
									{{$divisional ? $divisional->DESDE : 'N/A' }}
								</td>
								<td>
									{{$divisional ? $divisional->HASTA : 'N/A' }}
								</td>
								@include('admin::divisionales.partials.actions') 
							</tr>
						@endforeach
					</tbody>
				</table>
				@else
				<br>
				<div class="alert alert-info" style="margin:2px">No se han añadido nuevas fechas para esta divisional </div>
			@endif
	</div>


@include('admin::divisionales.partials.create')

@stop

@section('script')
<script>
	$(function(){

		$('#btn-add-date').click(function(){
			$('#form-add-date').submit();
		});

		$('.date-edit').click(function(){

		});

	});
</script>

@stop

