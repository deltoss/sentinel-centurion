@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::roles.page_titles.create')
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
			@lang('centurion::roles.headings.create_validation_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.validation_error')
		@endslot
	@endcomponent

	{{-- A create request needs to have the action of POST. --}}
	<form method="POST" action="{{ route('roles.store') }}">
		{{-- Good practice to include the below line to prevent security risks with forms --}}
		{{ csrf_field() }}

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">@lang('centurion::roles.headings.create')</div>
			</div>
			<div class="panel-body">
				<fieldset class="form-fieldset">
					<legend>
						<i class="fa-fw fas fa-feather"></i>
						@lang('centurion::roles.headings.general_information_group')
					</legend>
					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="name">@lang('centurion::roles.labels.name')</label>
								<input required type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="slug">@lang('centurion::roles.labels.slug')</label>
								<input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-control" />
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-sm btn-success">
					<i class="fa-fw fas fa-plus"></i> @lang('centurion::generics.buttons.create')
				</button> 
				<a href="{{ route('roles.index') }}" class="btn btn-sm btn-info">
					<i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
				</a> 
			</div>
		</div>
	</form>
@endsection
{{-- Marks the end of the content for the section --}}