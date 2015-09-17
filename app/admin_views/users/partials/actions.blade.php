{{Form::open([
  'action' => ['AdminUsersController@destroy', $user->id],
  'method' => 'DELETE',
  'class' => 'form-horizontal',
  'style' => 'display: inline',
  ])}}

  <div class="btn-group">

    @if(!$user->trashed())
      
        <a href="{{action('AdminUsersController@edit', $user->id)}}" class="btn btn-warning btn-xs">
          <span class="glyphicon glyphicon-pencil"></span> Editar
        </a>
        
      @unless(Auth::id() == $user->id)
        <button type="submit" class="btn btn-danger btn-xs">
          <span class="glyphicon glyphicon-remove"></span> Inhabilitar
        </button>
      @endunless
    @else
      <button type="submit" class="btn btn-success btn-xs">
        <span class="glyphicon glyphicon-ok"></span> Habilitar
      </button>
    @endif
  </div>
{{Form::close()}}
