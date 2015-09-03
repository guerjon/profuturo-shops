<!-- Modal -->
<div class="modal fade" id="add-to-cart-modal" tabindex="-1" role="dialog" aria-labelledby="add-to-cart-modal-title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="add-to-cart-modal-title"></h4>
      </div>
      <div class="modal-body">

        <div class="text-center">
          <img src="" class="img-rounded" id="furniture-cart-image">
        </div>
        <br>
        <p class="" id="furniture-cart-description">

        </p>


        <div class="alert alert-warning" style="display:none;">
          No puede ordenar más inventario de este mobiliario
        </div>

        {{Form::open([
          'id' => 'furniture-cart-form',
          'class' => 'form-horizontal',
          ])}}

          {{Form::hidden('furniture_id')}}

          <div class="col-xs-6">
            <p class="form-control-static">
              <strong id="furniture-cart-info"></strong>
            </p>
          </div>

          <div class="form-group">

            {{Form::label('quantity', 'Cantidad', ['class' => 'control-label col-xs-3'])}}
            <div class="col-xs-3">
              @if(Auth::user()->has_limit)
              {{Form::select('quantity', [], NULL, ['class' => 'form-control'])}}
              @else
              {{Form::number('quantity', NULL, ['class' => 'form-control'])}}
              @endif
            </div>
          </div>

      {{Form::hidden('order_id', $order_id)}}
        {{Form::close()}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" onclick="this.disabled = 'true';" class="btn btn-warning submit-btn">Añadir a mi carrito</button>
      </div>
    </div>
  </div>
</div>
