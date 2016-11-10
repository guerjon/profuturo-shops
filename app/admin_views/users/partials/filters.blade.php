{{ Form::open([
	'method' => 'GET',
	'class' => 'form-horizontal'
	]) }}
	{{ Form::hidden('active_tab', 'admin') }}
	<div class="form-group">
		<div class="col-xs-2">
			{{Form::text('admin[ccosto]', (Input::get('admin')['ccosto']), ['placeholder' => 'CCOSTOS','class' => 'form-control'])}}
		</div>
		<div class="col-xs-2">
			{{Form::text('admin[employee_number]', (Input::get('admin')['employee_number']), ['placeholder' => 'Número de Empleado','class' => 'form-control'])}}
		</div>
		<div class="col-xs-2">
			{{Form::text('admin[gerencia]',(Input::get('admin')['gerencia']),['placeholder' => 'Gerencia','class' => 'form-control'])}}
		</div>

		<div class="col-xs-1">
			<button type="submit" class="btn btn-block btn-default">
				<span class="glyphicon glyphicon-search"></span> 
			</button>
		</div>
	</div>
{{ Form::close() }}
