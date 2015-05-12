@extends('layouts.master')

@section('content')


<div class="text-right">
  {{link_to_action('AdminBusinessCardsController@create', 'Importar archivo Excel', [], ['class' => 'btn btn-default'])}}
</div>
<br>
@if($cards->count() == 0)

  <div class="alert alert-warning">
    No hay tarjetas que mostrar
  </div>
@else

  {{Form::open([
    'method' => 'GET',
    'class' => 'form-inline'
    ])}}

    <div class="form-group">
      {{Form::number('no_emp', Input::get('no_emp'), ['class' => 'form-control', 'placeholder' => 'Numero de empleado'])}}
    </div>

    <div class="form-group">
      {{Form::select('gerencia', [NULL => 'Todas las gerencias'] + $gerencias, Input::get('gerencia'), ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
      {{Form::submit('Filtrar', ['class' => 'btn btn-defaul'])}}
    </div>

  {{Form::close()}}

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>
            No. empleado
          </th>
          <th>
            Nombre
          </th>
          <th>
            Puesto
          </th>
          <th>
            Centro de costos
          </th>
          <th>
            Gerencia
          </th>
          <th>
            Fecha de ingreso
          </th>
          <th>

          </th>
        </tr>
      </thead>
      <tbody>
        @foreach($cards as $card)
        <tr>
          <td>
            {{$card->no_emp}}
          </td>
          <td>
            {{$card->nombre}}
          </td>
          <td>
            {{$card->nombre_puesto}}
          </td>
          <td>
            {{$card->ccosto}}
          </td>
          <td>
            {{$card->gerencia}}
          </td>
          <td>
            {{\Carbon\Carbon::createFromFormat('Y-m-d', $card->fecha_ingreso)->format('d/m/Y')}}
          </td>
          <td>
            {{Form::open([
              'class' => 'form-inline',
              'action' => ['AdminBusinessCardsController@destroy', $card->id],
              'method' => 'DELETE',
              ])}}

              @if($card->trashed())
              {{Form::submit('Habilitar', ['class' => 'btn btn-info'])}}
              @else
              {{Form::submit('inhabilitar',['class' => 'btn btn-sm btn-danger'])}}
              @endif

            {{Form::close()}}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="text-center">
    {{ $cards->appends(Input::all())->links() }}
  </div>


@endif

@stop
