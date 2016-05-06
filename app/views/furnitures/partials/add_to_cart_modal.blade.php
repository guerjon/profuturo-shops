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
          <img  src="" class="img-rounded" id="furniture-cart-image">
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
           
            
            <div class="col-xs-12">

              @if(Auth::user()->has_limit)
              <div class="form-group">
                {{Form::label('quantity', 'Cantidad', ['class' => 'control-label'])}}
                {{Form::number('quantity', NULL, ['class' => 'form-control'])}}
              </div>
              
              <div class="form-group">
                {{Form::label('company','Compañia',['class' => 'control-label'])}}
                {{Form::select('company',$companies,null,['class' => 'form-control'])}}  
              </div>

               <div class="form-group">
                {{Form::label('assets','Bienes',['class' => 'control-label'])}}
                {{Form::select('assets',$assets,null,['class' => 'form-control'])}}  
              </div>

              <div class="form-group">
                {{Form::label('ccostos','Centro de costos',['class' => 'control-label'])}}
                {{Form::text('ccostos',null,['class' => 'form-control'])}}  
              </div>

            <div id="color_div" category="{{$activeCategory ? $activeCategory->id : 0}}"  style="display:none">
                <div class="form-group">
                  {{Form::label('color','Color',['class' => 'control-label'])}}
                </div>
                <div class="form-group">
                  
                  <div class="col-md-2">                    
                      {{Form::radio('color','img/azul_palido.png',true)}}
                      <img src="img/azul_palido.png" class="img-responsive" alt="Azul palido">
                  </div>
                  
                  <div class="col-md-2">
                    {{Form::radio('color','img/azul_rey.png')}}
                    <img src="img/azul_rey.png" class="img-responsive" alt="Azul rey">
                  </div>
                
                
                  <div class="col-md-2">
                      {{Form::radio('color','img/gris.png')}}
                      <img src="img/gris.png" class="img-responsive" alt="Gris">
                  </div>
                  <div class="col-md-2">
                    {{Form::radio('color','img/negro.png')}}
                    <img src="img/negro.png"  class="img-responsive" alt="Negro">                 
                  </div>
                </div>                
            </div>  
              

              @endif
            </div>
          
        {{Form::close()}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button"  class="btn btn-warning submit-btn">Añadir a mi carrito</button>
      </div>
    </div>
  </div>
</div>
