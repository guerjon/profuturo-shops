@extends('layouts.master')

@section('style')

	<style>
		
		.welcome {
			width: 50em;
			height: 40em;
			/* position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -25em;
			margin-top: -19.3em; */
			margin: auto;
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

@endsection

@section('content')

	<div class="welcome">
		<img id="img-inside" src="/img/inside.png">
		<img id="img-inside" src="/img/text-home.png">

	</div>

@if(@$success)
	<div class="alert alert-success">
		{{$success}}	
	</div>
@endif

@if($user = Auth::user())
    @if(($user->role == 'admin' || $user->role=='manager'))
        @include('general_requests.partials.pendingModal')
    @endif
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

