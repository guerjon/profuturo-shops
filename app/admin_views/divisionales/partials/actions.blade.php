
<td>
	

	<button type="button" style="float:right"  data-date-edit="{{$divisional->id}}" class="btn btn-warning btn-xs date-edit" data-toggle="modal" data-target="#add-date">
    <span class="glyphicon glyphicon-pencil"></span> Editar
  </button>

</td>

<td>	
	 {{Form::open(array('action' =>['AdminDivisionalController@destroy',$divisional->id],
   'method' => 'delete'))}}

	    <button type="submit" class="btn btn-danger btn-xs date-delete">
	     <span class="glyphicon glyphicon-remove"></span> Eliminar
	    </button>

   {{Form::close()}}
</td>