@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::users.page_titles.create')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/information.css') }}">
	<script>
		$(document).ready(function() {
			function togglePasswordFields(createUserAsValue)
			{
				if (createUserAsValue == '1') {
					$('#password').prop('disabled', true).closest('.form-group').hide();
					$('#password_confirmation').prop('disabled', true).closest('.form-group').hide();
				}
				else {
					$('#password').prop('disabled', false).closest('.form-group').show();
					$('#password_confirmation').prop('disabled', false).closest('.form-group').show();
				}
			}

			$('#add_as').on('change', function(){
				var selectedValue = this.value;
				togglePasswordFields(selectedValue);
			});
			togglePasswordFields($('#add_as').val());
		});
	</script>
@endsection

@section('centurion-content')
	{{-- We use "@parent" to avoid overwriting the section, and only appends to it. --}}
	@parent

	<!-- if there are errors, they will show here -->
	@component('centurion::components/errors')
		@slot('title')
			@lang('centurion::users.headings.create_validation_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.validation_error')
		@endslot
	@endcomponent

	{{-- A create request needs to have the action of POST. --}}
	<form method="POST" action="{{ route('users.store') }}">
		{{-- Good practice to include the below line to prevent security risks with forms --}}
		{{ csrf_field() }}

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">@lang('centurion::users.headings.create')</div>
			</div>
			<div class="panel-body">
				<fieldset class="form-fieldset">
					<legend>
						<i class="fa-fw fas fa-id-card"></i>
						@lang('centurion::users.headings.account_information_group')
					</legend>
					<div class='row'>
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for="add_as">@lang('centurion::users.labels.add_user_as')</label>
								<select required class="form-control" id="add_as" name="add_as">
									<option value="" {{ (!old("add_as") ? "selected" : "") }}>@lang('centurion::users.labels.add_user_as_placeholder')</option>
									<option value="1" {{ (old("add_as") == 1 ? "selected" : "") }}>@lang('centurion::users.labels.add_user_as_unactivated_user_with_activation_email_sent')</option>
									<option value="2" {{ (old("add_as") == 2 ? "selected" : "") }}>@lang('centurion::users.labels.add_user_as_activated_user')</option>
									<option value="3" {{ (old("add_as") == 3 ? "selected" : "") }}>@lang('centurion::users.labels.add_user_as_deactivated_user')</option>
								</select>
							</div>
						</div>
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for="email">@lang('centurion::users.labels.email')</label>
								<input required type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="password">@lang('centurion::users.labels.password')</label>
								<input required type="password" name="password" id="password" value="{{ old('password') }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="password_confirmation">@lang('centurion::users.labels.confirm_password')</label>
								<input required type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control" />
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
								<input required type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control" />
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="last_name">@lang('centurion::users.labels.last_name')</label>
								<input required type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control" />
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-sm btn-success">
					<i class="fa-fw fas fa-plus"></i> @lang('centurion::generics.buttons.create')
				</button>
				<a href="{{ route('users.index') }}" class="btn btn-sm btn-info">
					<i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
				</a>
			</div>
		</div>
	</form>
@endsection
{{-- Marks the end of the content for the section --}}