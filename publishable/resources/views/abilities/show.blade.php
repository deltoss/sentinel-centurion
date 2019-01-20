@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::permissions.page_titles.show', ['name' => $ability->name])
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/information.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/popover.css') }}">
	<script src="{{ asset('vendor/centurion/js/popover.js') }}"></script>
	<script>
		function confirmDelete(event, element) {
            swal({
				type: "warning",
    			showCancelButton: true,
				title: "@lang('centurion::permissions.headings.delete_permission_confirmation')",
				text: "@lang('centurion::permissions.labels.delete_permission_confirmation')",
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

	<!-- if there are errors, they will show here -->
	@component('centurion::components/errors')
		@slot('title')
			@lang('centurion::permissions.headings.listing_requested_action_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.requested_action_error')
		@endslot
	@endcomponent

	@if($ability)
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">@lang('centurion::permissions.headings.show', ['name' => $ability->name])</div>
			</div>
			<div class="panel-body">
				<fieldset class="form-fieldset">
					<legend>
						<i class="fa-fw fas fa-feather"></i>
						@lang('centurion::permissions.headings.general_information_group')
					</legend>
					<div class='row'>
						<div class='col-sm-4'>
							<div class="info-group">
								<div class="info-label">
									@lang('centurion::permissions.labels.name')
								</div>
								<div class="info-value">
									{{ $ability->name }}
								</div>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class="info-group">
								<div class="info-label">
									@lang('centurion::permissions.labels.slug')
								</div>
								<div class="info-value">
									{{ $ability->slug }}
								</div>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class="info-group">
								<div class="info-label">
									@lang('centurion::permissions.labels.category')
								</div>
								<div class="info-value">
									{{ $ability->abilityCategory->name }}
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer clearfix">
				<span class="pull-left">
					<a href="{{ route('abilities.index') }}" class="btn btn-sm btn-default">
						<i class="fa-fw fas fa-angle-left"></i> @lang('centurion::generics.buttons.back')
					</a>
				</span>
				
				<span class="pull-right">
					<a class="btn btn-sm btn-default" href="{{ route('abilities.edit', $ability->id) }}">
						<i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::generics.buttons.edit')
					</a>

					<a tabindex="-1" class="btn btn-sm btn-default" role="button"
						data-toggle="button-popover">
						<i class="fa-fw fas fa-ellipsis-v"></i> @lang('centurion::generics.buttons.more')
					</a>
					
					<script type="text/template" class="popover-html-content">
						<a class="btn btn-sm btn-default" href="{{ route('abilities.users.assign', $ability->id) }}">
							<i class="fa-fw fas fa-user"></i> @lang('centurion::permissions.buttons.edit_permission_users')
						</a>

						<a class="btn btn-sm btn-default" href="{{ route('abilities.roles.assign', $ability->id) }}">
							<i class="fa-fw fas fa-users"></i> @lang('centurion::permissions.buttons.edit_permission_roles')
						</a>

						<!-- delete the ability (uses the destroy method DESTROY /Abilities/{id} -->
						<form method="POST" action="{{ route('abilities.destroy', $ability->id) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE" />
							<a href="#" type="submit" onclick="return confirmDelete(event, this)" class="btn btn-sm btn-default">
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