@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::profile.page_titles.change_password')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/information.css') }}">
@endsection

@section('centurion-content')
	{{-- We use "@parent" to avoid overwriting the section, and only appends to it. --}}
	@parent

	<!-- if there are errors, they will show here -->
	@component('centurion::components/errors')
		@slot('title')
			@lang('centurion::profile.headings.change_password_validation_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.validation_error')
		@endslot
	@endcomponent

	<form method="POST" action="{{ route('profile.change_password') }}">

		{{-- Good practice to include the below line to prevent security risks with forms --}}
		{{ csrf_field() }}
		<!-- 
			Note for Laravel resource controller, to access the update method of a resource controller,
			you need to use the PUT action. It can't be POST/GET/PUT/PATCH.
			
			However, HTML5 does not support submitting as DELETE/PUT/PATCH methods.
			It only supports POST and GET, however Laravel Framework supports DELETE/PUT/PATCH
			by having a hidden field "_method", which value tells Laravel the actual form action
			to be PUT in this case.
		-->
		<input type="hidden" name="_method" value="PUT" />
		{{--
			Alternatively:
			{{ method_field('PUT') }}
		--}}

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">@lang('centurion::profile.headings.change_password')</div>
			</div>
			<div class="panel-body">
				<fieldset class="form-fieldset">
					<div class='row'>
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for="old_password">@lang('centurion::profile.labels.old_password')</label>
								<input required type="password" name="old_password" id="old_password" value="{{ old('old_password') }}"
										class="form-control" />
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for="new_password">@lang('centurion::profile.labels.new_password')</label>
								<input required type="password" name="new_password" id="new_password" value="{{ old('new_password') }}"
										class="form-control" />
							</div>
						</div>
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for="new_password_confirmation">@lang('centurion::profile.labels.confirm_new_password')</label>
								<input required type="password" name="new_password_confirmation" id="new_password_confirmation" value="{{ old('confirm_new_password') }}"
										class="form-control" />
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-sm btn-warning">
					<i class="fa-fw fas fa-sync-alt"></i> @lang('centurion::generics.buttons.update')
				</button>
				<a href="{{ route('profile.index') }}" class="btn btn-sm btn-info">
					<i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
				</a> 
			</div>
		</div>
	</form>
@endsection
{{-- Marks the end of the content for the section --}}