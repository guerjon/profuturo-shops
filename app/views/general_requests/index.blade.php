@extends('layouts.master')

@section('content')

@if($requests->count() == 0)
<div class="alert alert-info">
  Usted no ha hecho ninguna solicitud
</div>
@else
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          # de sol.
        </th>
        <th>
          Título proyecto
        </th>
        <th>
          Estatus
        </th>
        <th>
          Presupuesto
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach($requests as $request)
      <tr>
        <td>
          {{$request->id}}
        </td>
        <td>
          {{$request->project_title}}
        </td>
        <td>
          Pendiente
        </td>
        <td>
          {{ $request->unit_price * $request->quantity}}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

<div class="text-right">

  <a data-toggle="modal" data-target="#create-request-modal" class="btn btn-warning">Crear nueva solicitud</a>

</div>

<div class="modal fade" id="create-request-modal" tabindex="-1" role="dialog" aria-labelledby="create-request-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="create-request-label">Crear una nueva solicitud</h4>
      </div>
      <div class="modal-body">
        {{Form::open([
          'action' => 'GeneralRequestsController@store'
          ])}}

          <div class="step-div start-div">

            <h5>Por favor, indícanos tus datos personales para contactarte</h5>

            <div class="form-group">
              {{Form::label('employee_name', 'Nombre')}}
              {{Form::text('employee_name', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_number', 'Número de empleado')}}
              {{Form::number('employee_number', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_email', 'Correo electrónico')}}
              {{Form::email('employee_email', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_ext', 'Extensión')}}
              {{Form::number('employee_ext', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_cellphone', 'Celular')}}
              {{Form::text('employee_cellphone', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group text-right">
              <button type="button" class="btn btn-warning">Siguiente</button>
            </div>
          </div>


          <div class="step-div">
            <h5>Platícanos sobre tu proyecto</h5>

            <div class="form-group">
              {{Form::label('project_title', 'Nombre del proyecto')}}
              {{Form::text('project_title', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('project_dest', '¿A quién va dirigido?')}}
              {{Form::text('project_dest', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('project_date', 'Fecha del evento')}}
              {{Form::input('date', 'project_date', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group text-right">
              <button type="button" class="btn btn-warning">Siguiente</button>
            </div>
          </div>


          <div class="step-div">
            <h5>¿Cómo podemos ayudarte?</h5>

            <div class="form-group">
              {{Form::label('kind', 'Nombre del proyecto')}}
              {{Form::select('kind', ['Producto', 'Servicio'], NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('quantity', 'Cantidad')}}
              {{Form::number('quantity', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('unit_price', 'Precio unitario')}}
              {{Form::number('unit_price', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('budget', 'Presupuesto')}}
              {{Form::number('budget', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('deliver_date', 'Fecha de entrega')}}
              {{Form::input('date', 'deliver_date', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('distribution_list', 'Lista de distribución')}}
              <div class="radio">
                <label>
                  {{Form::radio('distribution_list', 1, TRUE, ['data-next-div' => 'disabled'])}} Sí
                </label>
              </div>

              <div class="radio">
                <label>
                  {{Form::radio('distribution_list', 0, false, ['data-next-div' => 'disabled'])}} No
                </label>
              </div>

              <div class="radio">
                <label>
                  {{Form::radio('distribution_list', 2, false, ['data-next-div' => 'disabled'])}} Pendiente
                </label>
              </div>
            </div>

            <div class="form-group text-right">
              <button type="button" class="btn btn-warning">Siguiente</button>
            </div>
          </div>

          <div class="step-div">
            <h5>¿Cuáles son tus expectativas</h5>

            <div class="form-group">
              {{Form::textarea('comments', NULL, ['class' => 'form-control', 'rows' => 3])}}
            </div>

            <div class="form-group text-right">
              {{Form::submit('Guardar', ['class' => 'btn btn-warning'])}}
            </div>
          </div>
        {{ Form::close() }}
      </div>

    </div>
  </div>
</div>

@stop

@section('script')
<script src="/js/advancedStepper.js"></script>
<script charset="utf-8">
  $(function(){
    $('#create-request-modal').advancedStepper();
  });
</script>
@stop
