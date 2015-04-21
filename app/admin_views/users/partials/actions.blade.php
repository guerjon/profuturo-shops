{{Form::open([
  'action' => ['AdminUsersController@destroy', $user->id],
  'method' => 'DELETE',
  'class' => 'form-horizontal',
  'style' => 'display: inline',
  ])}}

  <div class="btn-group">

    @if(!$user->trashed())
      {{link_to_action('AdminUsersController@edit', 'Editar', [$user->id], ['class' => 'btn btn-sm btn-default'])}}
      @unless(Auth::id() == $user->id)
      {{Form::submit('Deshabilitar', ['class' => 'btn btn-sm btn-danger'])}}
      @endunless
    @else
      {{Form::submit('Habilitar', ['class' => 'btn btn-sm btn-info'])}}
    @endif
  </div>
{{Form::close()}}
