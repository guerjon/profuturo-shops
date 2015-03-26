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
	</div>

@stop
