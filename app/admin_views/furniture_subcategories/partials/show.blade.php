{{ Form::open([
  'method' => 'DELETE',
  'class' => 'form-horizontal',
  'id' => 'form-subcategory-delete'
  ]) }}

<div id="subcategories" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Subcategorias</h4>
      </div>
      <div class="modal-body">
        <center>
          <div class="furniture_subcategories"></div>  
        </center>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>