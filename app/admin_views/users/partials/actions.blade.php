{{Form::open([
  'action' => ['AdminUsersController@destroy', $user->id],
  'method' => 'DELETE',
  'class' => 'form-horizontal',
  'style' => 'display: inline',
  ])}}

  <div class="btn-group">

    @if(!$user->trashed())
      
        <a href="{{action('AdminUsersController@edit', $user->id)}}" class="btn btn-warning btn-xs" style="height:25px;margin:2px;padding-top:2px;" >
          <span class="glyphicon glyphicon-pencil" style="padding-top:2px;"></span> Editar
        </a>

      @unless(Auth::id() == $user->id)
        <button type="submit" class="btn btn-danger btn-xs" style="height:25px;margin:2px">
          <span class="glyphicon glyphicon-remove"></span> Inhabilitar
        </button>
      @endunless
    @else
      <button type="submit" class="btn btn-success btn-xs" style="height:25px;margin:2px">
        <span class="glyphicon glyphicon-ok"></span> Habilitar
      </button>
    @endif
  </div>
{{Form::close()}}
