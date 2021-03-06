@extends('layouts.master')

@section('content')

<div class="container">
	<h3>Añadir fecha para pedidos corporativo</h3>
	<div class="row">
		<div class="col col-xs-9"></div>
		<div class="col col-xs-3 text-right" >
			<div class="btn btn-primary" data-toggle="modal" data-target="#date-corporation-modal">
				<span class="glyphicon glyphicon-plus"></span>
				Añadir fecha
			</div>
		</div>
	<hr>
	</div>
	@if($dates_corporation->count() == 0)
		<div class="alert alert-info">
			<center>
				No se han agregado fechas aun.
			</center>
		</div>
	@else
		<table class="table table-striped">
			<thead>
				<th>
					Tipo
				</th>
				<th>
					Desde
				</th>
				<th>
					Hasta
				</th>
				<th>
					
				</th>
			</thead>
			<tbody>
				@foreach($dates_corporation as $date_corporation)
					<tr>
						<td>
                            {{
                                $date_corporation->kind == 'papeleria' ? 'Papelería' : 'Tarjetas'
                            }}
                        </td>				
						<td>
							{{$date_corporation->since}}
						</td>
						<td>
							{{$date_corporation->until}}
						</td>
						<td>	
							{{Form::open(array('action' =>['AdminDatesCorporationController@destroy',$date_corporation->id],
						   'method' => 'delete'))}}

							    <button type="submit" class="btn btn-danger btn-xs date-delete">
							     <span class="glyphicon glyphicon-remove"></span> Eliminar
							    </button>
							{{ Form::close() }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
</div>

<!-- Modal -->
<div id="date-corporation-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Añadir fecha</h4>
      </div>
      <div class="modal-body">
		{{Form::open(['action' => 'AdminDatesCorporationController@store','id' => 'form-add-date'])}}  
          
	        <div class="form-group">
				{{Form::label('since','Desde')}}
				{{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'from' ])}}
			</div>
			<div class="form-group">
				{{Form::label('until','Hasta')}}
				{{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->addMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
			</div>
			<div class="form-group">
			  {{Form::label('kind','Tipo')}}
			  {{Form::select('kind', ['papeleria' => 'Papelería', 'tarjetas' => 'Tarjetas Presentación'], null, ['class' => 'form-control'])}}
			</div>
			<div class="form-group">
	        	{{Form::label('comments','Comentarios')}}
	        	{{Form::textArea('comments',null,['class' => 'form-control'])}}
	        	<p class="help-block">Estos comentarios seran enviados por email a los usuarios de corporativo.</p>
        	</div>
        

        {{Form::close()}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn-add-date">Añadir</button>

      </div>
    </div>

  </div>
</div>

@stop


@section('script')
<script>
	$(function(){
		$('#btn-add-date').click(function(){
			$('#form-add-date').submit();
		});
	});
</script>

@stop
