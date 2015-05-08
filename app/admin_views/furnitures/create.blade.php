@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">

    @if($furniture->getErrors()->count() > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach($furniture->getErrors()->all() as $error)
        <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
    @endif
    {{Form::model($furniture, [
      'action' => $furniture->exists ? ['AdminFurnituresController@update', $furniture->id] : 'AdminFurnituresController@store',
      'method' => $furniture->exists ? 'PUT' : 'POST',
      'files' => true
      ])}}

      <div class="form-group">
        {{Form::label('name', 'Nombre corto')}}
        {{Form::text('name', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('description', 'Descripción')}}
        {{Form::textarea('specific_description', NULL, ['class' => 'form-control', 'rows' => 3])}}
      </div>

      <div class="form-group">
        {{Form::label('unitary', 'Unitario')}}
        {{Form::number('unitary', NULL, ['class' => 'form-control'])}}
      </div>


      <div class="form-group">
        {{Form::label('key', 'Clave')}}
        {{Form::text('key', NULL, ['class' => 'form-control'])}}
      </div>

       <div class="form-group">
        {{Form::label('delivery_time', 'Tiempo de entrega')}}
        {{Form::text('delivery_time', NULL, ['class' => 'form-control'])}}
      </div>

       <div class="form-group">
        {{Form::label('business_conditions', 'Condiciones comerciales')}}
        {{Form::text('business_conditions', NULL, ['class' => 'form-control'])}}
      </div>


      <div class="form-group">
        {{Form::label('category_id', 'Categoría')}}
        {{Form::select('category_id', ($furniture->category ? [] : [NULL => 'Sin especificar']) + FurnitureCategory::lists('name', 'id'), NULL,
          ['class' => 'form-control'])}}
      </div>



      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-warning btn-lg'])}}
      </div>
    {{Form::close()}}
  </div>
</div>

@stop