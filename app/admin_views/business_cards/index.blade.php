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
            Nombre C. Costo
          </th>
          <th>
            Puesto
          </th>
          <th>
            Gerencia
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
            {{$card->nombre_ccosto}}
          </td>
          <td>
            {{$card->nombre_puesto}}
          </td>
          <td>
            {{$card->gerencia}}
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
    {{ $cards->links() }}
  </div>


@endif

@stop
