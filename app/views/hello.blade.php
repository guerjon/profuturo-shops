@extends('layouts.master')

@section('content')

	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		.welcome {
			width: 50em;
			height: 40em;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -25em;
			margin-top: -19.3em;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
		#img-inside{
			width: 100%;
		}
	</style>
	<div class="welcome">
		<img id="img-inside" src="/img/inside.png">
		<img id="img-inside" src="/img/text-home.png">

	</div>

@if(@$success)
	<div class="alert alert-success">
		{{$success}}	
	</div>
@endif

 

	@if(Auth::validate($credentials))

<!--<div class="modal fade" id="request-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Introducir contraseña nueva</h4>
      </div>
      <div class="modal-body">
      			@if (count($errors) > 0)
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
						@endif
					{{ Form::open([
	          'action' => 'HomeController@postPassword',
	          'class' => 'form-horizontal',
	          'id' => 'form-update-password'
	          ])}}
						
							<div class="col-xs-12">
								<div class="form-group">
			          	{{Form::label('email','Correo electrónico')}}
			       			{{Form::email('email',NULL,['class' => 'form-control','required' => 'true'])}}
			          </div>
								
								<div class="form-group">
			       			{{ Form::label('password', 'Contraseña')}} 
			        		{{ Form::password('password',['class' => 'form-control','required' => 'true'])}}
			    			</div>

						    <div class="form-group">
						       {{ Form::label('password_confirmation', 'Confirmar contraseña') }}
									 {{ Form::password('password_confirmation',['class' => 'form-control','required' => 'true'])}}
						    </div>          
			      		
		      		</div>
						{{Form::close()}}
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning" id="submit-btn">Guardar</button>
      </div>
    </div>
  </div>
</div>-->
@endif


					
@stop

@section('script')
<script>
	$(function(){
		var modal =  $('#request-modal');
		if(true){
			modal.modal('show');	
		}
	

	$.validator.setDefaults({
		focusCleanup: true
	});
	$( "#form-update-password" ).validate({
  rules: {
    password: "required",
    password_confirmation: {
      equalTo: "#password"
    }
  },
  messages:{
  	email: { email:"Por favor introduce un correo valido",
  					 required:"El email es requerido"
  	},
  	password:{
  		required:'La contaseña es requerida'
  	},
  	password_confirmation: "Las contraseñas deben de ser iguales"
  }
});
	$('#password_confirmation').change()
	$('#password_confirmation-error')
	});


	$('#submit-btn').click(function(){
    	$('#form-update-password').submit();
  	});

</script>

@stop

