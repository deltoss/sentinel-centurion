@extends('centurion::layouts/auth_layout')

@section('centurion-title')
	@lang('centurion::activation.page_titles.activation_error')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/vertical-center.css') }}">
@endsection

@section('centurion-content')
<div class="vertical-center">
    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="alert alert-danger">
					<div class="text-center">
						<h3>
							<i class="fa-fw fas fa-exclamation-circle fa-4x" style="color: #a94442;"></i>
						</h3>
						<h3>@lang('centurion::activation.headings.activation_error')</h3>
					</div>
					@lang('centurion::activation.labels.issues_activating_account')<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{!! $error !!}</li>
						@endforeach
					</ul>
					{{--
						Alternative Code - with composer package "laravelcollective/html" installed:
						{{ HTML::ul($errors->all()) }}
					--}}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
{{-- Marks the end of the content for the section --}}