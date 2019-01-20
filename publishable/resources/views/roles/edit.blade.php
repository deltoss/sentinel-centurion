@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::roles.page_titles.edit', ['name' => $role->name])
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
			@lang('centurion::roles.headings.edit_validation_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.validation_error')
		@endslot
	@endcomponent

	<form method="POST" action="{{ route('roles.update', $role->id) }}">
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
				<div class="panel-title">@lang('centurion::roles.headings.edit', ['name' => $role->name])</div>
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
								<input required type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="Slug">@lang('centurion::roles.labels.slug')</label>
								<input type="text" name="slug" id="slug" value="{{ old('slug', $role->slug) }}" class="form-control" />
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-sm btn-warning">
					<i class="fa-fw fas fa-sync-alt"></i> @lang('centurion::generics.buttons.update')
				</button>
				<a href="{{ route('roles.index') }}" class="btn btn-sm btn-info">
					<i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
				</a> 
			</div>
		</div>
	</form>
@endsection
{{-- Marks the end of the content for the section --}}