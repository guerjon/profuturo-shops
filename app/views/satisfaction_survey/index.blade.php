@extends('layouts.master')

@section('content')
	
	{{Form::open([
			'action' => 'SatisfactionSurveyController@postSatisfactionSurvey',
			'role' => 'form',
			'class' => 'form-inline',	
	])}}

 
{{Form::close()}}	
@stop


