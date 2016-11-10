<div class="container-fluid">
	<div class="table-responsive">

		<table class="table table-striped">

			<thead>
				<tr>
					<th>C.Costos</th>
					<th>Gerencia</th>
					<th>Linea de negocio</th>
					<th>Email</th>
					<th>Divisional</th>
					<th>Región</th>
					<th># Empleado</th>
					<th></th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				@foreach ($users as $user)
					<tr>
						 <td>{{$user->ccosto}}</td>
						 <td>
						 	{{$user->gerencia}}
						 </td>
						 <td>
						 	{{$user->linea_negocio}}
						 </td>
						 <td>
						 	{{$user->email}}
						 </td>
						 <td>
						 	{{$user->divisional ? $user->divisional->name : 'N/A'}}
						 </td>
						 <td>
						 	{{$user->region ? $user->region->name : 'N/A'}}
						 </td>
						 <td>
						 	{{$user->num_empleado}}
						 </td>
						<td>
							@include('admin::users.partials.actions', ['user' => $user])
						</td>
					 </tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<div class="text-center">
	{{ $users->appends(['active_tab' => 'admin'])->links()}}
</div>
