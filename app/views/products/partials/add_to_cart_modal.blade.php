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
          <img src="" class="img-rounded" id="product-cart-image">
        </div>
        <br>
        <p class="" id="product-cart-description">

        </p>


        {{Form::open([
          'class' => 'form-horizontal',
          ])}}

          {{Form::hidden('product_id')}}
          <div class="form-group">

            {{Form::label('price', 'Precio', ['class' => 'control-label col-xs-2 col-xs-offset-4'])}}

            <div class="col-xs-2">
              <p class="form-control-static" id="product-cart-price">

              </p>
            </div>
            {{Form::label('quantity', 'Cantidad', ['class' => 'control-label col-xs-2'])}}
            <div class="col-xs-2">
              {{Form::select('quantity', [1 => 1, 2=>2, 3=> 3, 4=>4, 5=>5], NULL, ['class' => 'form-control'])}}
            </div>
          </div>
        {{Form::close()}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary submit-btn">Añadir a mi carrito</button>
      </div>
    </div>
  </div>
</div>
