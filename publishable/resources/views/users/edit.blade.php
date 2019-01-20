@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::users.page_titles.edit', ['first_name' => $user->first_name, 'last_name' => $user->last_name])
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
			@lang('centurion::users.headings.edit_validation_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.validation_error')
		@endslot
	@endcomponent

	<form method="POST" action="{{ route('users.update', $user->id) }}">

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
				<div class="panel-title">@lang('centurion::users.headings.edit', ['first_name' => $user->first_name, 'last_name' => $user->last_name])</div>
			</div>
			<div class="panel-body">
				<fieldset class="form-fieldset">
					<legend>
						<i class="fa-fw fas fa-id-card"></i>
						@lang('centurion::users.headings.account_information_group')
					</legend>
					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="email">@lang('centurion::users.labels.email')</label>
								<input required type="text" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" />
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset class="form-fieldset">
					<legend>
						<i class="fa-fw fas fa-user"></i>
						@lang('centurion::users.headings.personal_information_group')
					</legend>
					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="first_name">@lang('centurion::users.labels.first_name')</label>
								<input required type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="last_name">@lang('centurion::users.labels.last_name')</label>
								<input required type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control" />
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-sm btn-warning">
					<i class="fa-fw fas fa-sync-alt"></i> @lang('centurion::generics.buttons.update')
				</button>
				<a href="{{ route('users.index') }}" class="btn btn-sm btn-info">
					<i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
				</a> 
			</div>
		</div>
	</form>
@endsection
{{-- Marks the end of the content for the section --}}