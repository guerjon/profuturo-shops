@extends('layouts.master')

@section('content')

@if($errors->count() > 0)
<div class="alert alert-danger">
  <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
  </ul>
</div>
@endif

<h3>Centros de costos</h3>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          Clave
        </th>
        <th>
          Centro de costos
        </th>
      </tr>
    </thead>

    <tbody>
      @foreach($users as $user)
      <tr>
        <td>
          {{$user->ccosto}}
          <span class="label label-default">{{$user->role == 'user_requests' ? 'Proyectos' : 'Papelería'}}</span>
        </td>
        <td>
          {{$user->gerencia}}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<h4>Administración</h4>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          Correo electrónico
        </th>
        <th>
          Nombre completo
        </th>
      </tr>
    </thead>

    <tbody>
      @foreach($admins as $user)
      <tr>
        <td>
          {{$user->email}}
          @if($user->is_admin)
          <span class="label label-info">Admin</span>
          @endif
        </td>
        <td>
          {{$user->first_name}} {{$user->last_name}}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="text-right">
  {{link_to_action('AdminUsersController@create', 'Crear nuevo usuario', [], ['class' => 'btn btn-warning'])}}
</div>



<!-- Modal -->
<div class="modal fade" id="add-budget-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        {{Form::open([
          'action' => 'AdminBudgetsController@store'
          ])}}

        {{Form::hidden('user_id')}}
        <div class="form-group">
          {{Form::text('amount', NULL, ['class' => 'form-control', 'placeholder' => 'Cantidad a asignar'])}}
        </div>
        <div class="form-group text-right">
          <button type="button" data-dismiss="modal" class="btn btn-sm btn-default">Cancelar</button>
          <button type="submit" class="btn btn-sm btn-warning">Asignar</button>
        </div>
        {{Form::close()}}
      </div>

    </div>
  </div>
</div>


@stop

@section('script')
<script>
$(function(){
  $('button[data-user-id]').click(function(){
    $('#add-budget-modal').modal('show');
    $('input[name="user_id"]').val($(this).attr('data-user-id'));
  });
});
</script>
@stop
