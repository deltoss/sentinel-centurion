@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::permissions.page_titles.edit', ['name' => $ability->name])
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

	<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/centurion/plugins/select2/css/select2.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/centurion/plugins/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
	<script src="{{ asset('/vendor/centurion/plugins/select2/js/select2.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/information.css') }}">
	<script>
		$(document).ready(function(){
			var dataArr = {!! $abilityCategories !!};
			// The previously selected value
			var previouslySelectedValue = "{{ old('ability_category', $ability->ability_category_id) }}";
			var foundPreviouslySelectedValue = false;
			// Convert the data array into an array format suitable for select2
			dataArr = $.map(dataArr, function (obj) {
				obj.text = obj.text || obj.name;
				if (obj.id == previouslySelectedValue)
				{
					obj.selected = true;
					foundPreviouslySelectedValue = true;
				}

				return obj;
			});
			
			var $selectElement = $("#ability_category");
			$selectElement.select2({
				width: "100%",
				theme: "bootstrap",
				data: dataArr,
				tags: true,
				allowClear: true,
				placeholder: "@lang('centurion::permissions.labels.category_placeholder')",
				selectOnClose: true,
			})
			// If previous value is not blank and was not found,
			// its a dynamically created option. So we re-create
			// it and select it
			if (foundPreviouslySelectedValue == false && previouslySelectedValue)
			{
				// Create a DOM Option and pre-select by default
				var newOption = new Option(previouslySelectedValue, previouslySelectedValue, true, true);
				// Append it to the select
				$selectElement.append(newOption).trigger('change');
			}
		});
	</script>
@endsection

@section('centurion-content')
	{{-- We use "@parent" to avoid overwriting the section, and only appends to it. --}}
	@parent

	<!-- if there are errors, they will show here -->
	@component('centurion::components/errors')
		@slot('title')
			@lang('centurion::permissions.headings.edit_validation_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.validation_error')
		@endslot
	@endcomponent

	<form method="POST" action="{{ route('abilities.update', $ability->id) }}">
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
				<div class="panel-title">@lang('centurion::permissions.headings.edit', ['name' => $ability->name])</div>
			</div>
			<div class="panel-body">
				<fieldset class="form-fieldset">
					<legend>
						<i class="fa-fw fas fa-feather"></i>
						@lang('centurion::permissions.headings.general_information_group')
					</legend>
					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="name">@lang('centurion::permissions.labels.name')</label>
								<input required type="text" name="name" id="name" value="{{ old('name', $ability->name) }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="slug">@lang('centurion::permissions.labels.slug')</label>
								<input required type="text" name="slug" id="slug" value="{{ old('slug', $ability->slug) }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="ability_category">@lang('centurion::permissions.labels.category')</label>
								<select required name="ability_category" id="ability_category" class="form-control">
									<option></option>
								</select>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-sm btn-warning">
					<i class="fa-fw fas fa-sync-alt"></i> @lang('centurion::generics.buttons.update')
				</button>
				<a href="{{ route('abilities.index') }}" class="btn btn-sm btn-info">
					<i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
				</a> 
			</div>
		</div>
	</form>
@endsection
{{-- Marks the end of the content for the section --}}