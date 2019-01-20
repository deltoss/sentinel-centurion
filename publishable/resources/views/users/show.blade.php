@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::users.page_titles.show', ['first_name' => $user->first_name, 'last_name' => $user->last_name])
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/information.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/popover.css') }}">
	<script src="{{ asset('vendor/centurion/js/popover.js') }}"></script>
	<script>
		function confirmDeactivate(event, element) {
            swal({
				type: "warning",
    			showCancelButton: true,
				title: "@lang('centurion::users.headings.deactivate_user_confirmation')",
				text: "@lang('centurion::users.labels.deactivate_user_confirmation')",
				confirmButtonText: "@lang('centurion::generics.buttons.yes')",
				cancelButtonText: "@lang('centurion::generics.buttons.cancel')",
            }).then(function(result) {
                if (result.value)
                {
					var $form = $(element).closest("form");
					var form = $form[0];
					// Dismissable bootstrap popover removes the
					// form from the dom when the popover closes
					// (i.e. when the alert shows up)
					// If you submit the form while it's not in DOM,
					// the submission would get cancelled.
					// As such, the code below appends it if it's
					// not within the DOM
					if (document.body.contains(form) == false)
					{
						form.style.display = "none";
						document.body.appendChild(form);
					}
					$form.trigger('submit');
                }
            });
            return false;
        }
		function confirmDelete(event, element) {
            swal({
				type: "warning",
    			showCancelButton: true,
				title: "@lang('centurion::users.headings.delete_user_confirmation')",
				text: "@lang('centurion::users.labels.delete_user_confirmation')",
				confirmButtonText: "@lang('centurion::generics.buttons.yes')",
				cancelButtonText: "@lang('centurion::generics.buttons.cancel')",
            }).then(function(result) {
                if (result.value)
                {
					var $form = $(element).closest("form");
					var form = $form[0];
					// Dismissable bootstrap popover removes the
					// form from the dom when the popover closes
					// (i.e. when the alert shows up)
					// If you submit the form while it's not in DOM,
					// the submission would get cancelled.
					// As such, the code below appends it if it's
					// not within the DOM
					if (document.body.contains(form) == false)
					{
						form.style.display = "none";
						document.body.appendChild(form);
					}
					$form.trigger('submit');
                }
            });
            return false;
        }
	</script>
@endsection

@section('centurion-content')
	{{-- We use "@parent" to avoid overwriting the section, and only appends to it. --}}
	@parent

    <!-- will be used to show any messages -->
	@component('centurion::components/message')
	@endcomponent

	<!-- if there are errors, they will show here -->
	@component('centurion::components/errors')
		@slot('title')
			@lang('centurion::users.headings.listing_requested_action_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.requested_action_error')
		@endslot
	@endcomponent

	@if($user)
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">@lang('centurion::users.headings.show', ['first_name' => $user->first_name, 'last_name' => $user->last_name])</div>
			</div>
			<div class="panel-body">
				<fieldset class="form-fieldset">
					<legend>
						<i class="fa-fw fas fa-id-card"></i>
						@lang('centurion::users.headings.account_information_group')
					</legend>
					<div class='row'>
						<div class='col-sm-4'>
							<div class="info-group">
								<div class="info-label">
									@lang('centurion::users.labels.email')
								</div>
								<div class="info-value">
									<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
								</div>
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
							<div class="info-group">
								<div class="info-label">
									@lang('centurion::users.labels.first_name')
								</div>
								<div class="info-value">
									{{ $user->first_name }}
								</div>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class="info-group">
								<div class="info-label">
									@lang('centurion::users.labels.last_name')
								</div>
								<div class="info-value">
									{{ $user->last_name }}
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer clearfix">
				<span class="pull-left">
					<a href="{{ route('users.index') }}" class="btn btn-sm btn-default">
						<i class="fa-fw fas fa-angle-left"></i> @lang('centurion::generics.buttons.back')
					</a> 
				</span>
				
				<span class="pull-right">
					<a class="btn btn-sm btn-default" href="{{ route('users.edit', $user->id) }}">
						<i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::generics.buttons.edit')
					</a>

					<a tabindex="-1" class="btn btn-sm btn-default" role="button"
						data-toggle="button-popover">
						<i class="fa-fw fas fa-ellipsis-v"></i> @lang('centurion::generics.buttons.more')
					</a>

					<script type="text/template" class="popover-html-content">
						<a class="btn btn-sm btn-default" href="{{ route('users.roles.assign', $user->id) }}">
							<i class="fa-fw fas fa-users"></i> @lang('centurion::users.buttons.edit_user_roles')
						</a>

						<a class="btn btn-sm btn-default" href="{{ route('users.abilities.assign', $user->id) }}">
							<i class="fa-fw fas fa-th-list"></i> @lang('centurion::users.buttons.edit_user_permissions')
						</a>

						@if (Activation::completed($user))
							<!-- deactivate the user (uses the destroy method DESTROY -->
							<form method="POST" action="{{ route('users.deactivate', $user->id) }}">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="DELETE" />
								<button type="submit" onclick="return confirmDeactivate(event, this)" class="btn btn-sm btn-default">
									<i style="color: crimson;" class="fa-fw fas fa-power-off"></i> @lang('centurion::generics.buttons.deactivate')
								</button>
							</form>

							<!-- send user the reset password email to recover their account -->
							<form method="POST" action="{{ route('users.email.reset_password', $user->id) }}">
								{{ csrf_field() }}

								<button type="submit" class="btn btn-sm btn-default">
									<i class="fa-fw fas fa-unlock-alt"></i> @lang('centurion::users.buttons.send_user_reset_password_email')
								</button>
							</form>
						@else
							<!-- activate the user -->
							<form method="POST" action="{{ route('users.activate', $user->id) }}">
								{{ csrf_field() }}
								<button type="submit" class="btn btn-sm btn-default">
									<i style="color: limegreen;" class="fa-fw fas fa-power-off"></i> @lang('centurion::generics.buttons.activate')
								</button>
							</form>

							<!-- send user the activation email to activate their account -->
							<form method="POST" action="{{ route('users.email.activate', $user->id) }}">
								{{ csrf_field() }}

								<button type="submit" class="btn btn-sm btn-default">
									<i class="fa-fw fas fa-envelope"></i> @lang('centurion::users.buttons.send_user_activation_email')
								</button>
							</form>
						@endif

						<!-- delete the user (uses the destroy method DESTROY /Users/{id} -->
						<form method="POST" action="{{ route('users.destroy', $user->id) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE" />
							<button type="submit" onclick="return confirmDelete(event, this)" class="btn btn-sm btn-default">
								<i class="fa-fw far fa-trash-alt"></i> @lang('centurion::generics.buttons.delete')
							</button>
						</form>
					</script>
				</span>
			</div>
		</div>
	@endif
@endsection
{{-- Marks the end of the content for the section --}}