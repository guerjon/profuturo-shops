<!-- Modal -->
<div class="modal fade" id="request-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Solicitud <span class="request-id"></span></h4>
      </div>
      <div class="modal-body">

        <h3>Asignacion de asesores</h3>
        <h4>Administración</h4>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          Gerencia
        </th>
        <th>
         Centro de costos
        </th>
         <th>
         Situación critica
        </th>
         <th>
         Asignar
        </th>

      </tr>
    </thead>

    <tbody>
      @foreach($admins as $user)
      <tr>
        <td>
          {{$user->gerencia}}
       
        </td>
        <td>
          {{$user->ccosto}}
        </td>
        <td>
          <div class="managers" data-path="{{public_path()}}"></div>
        </td>
         <td>
             <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default detail-btn" data-request-id="{{$request->id}}">Asignar</button>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-warning">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
@section('script')
<script>
$(function(){
  $('.managers').raty(
    
  );
})
</script>
@stop
