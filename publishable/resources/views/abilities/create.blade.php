@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::permissions.page_titles.create')
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
			// The previously selected value if error occurred
			var previouslySelectedValue = "{{ old('ability_category') }}";
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
			@lang('centurion::permissions.headings.create_validation_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.validation_error')
		@endslot
	@endcomponent

	{{-- A create request needs to have the action of POST. --}}
	<form method="POST" action="{{ route('abilities.store') }}">
		{{-- Good practice to include the below line to prevent security risks with forms --}}
		{{ csrf_field() }}

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">@lang('centurion::permissions.headings.create')</div>
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
								<input required type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="slug">@lang('centurion::permissions.labels.slug')</label>
								<input required type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-control" />
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
				<button type="submit" class="btn btn-sm btn-success">
					<i class="fa-fw fas fa-plus"></i> @lang('centurion::generics.buttons.create')
				</button>
				<a href="{{ route('abilities.index') }}" class="btn btn-sm btn-info">
					<i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
				</a> 
			</div>
		</div>
	</form>
@endsection
{{-- Marks the end of the content for the section --}}