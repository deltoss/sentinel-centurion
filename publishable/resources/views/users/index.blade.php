@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::users.page_titles.listing')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

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

	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h3 class="panel-title pull-left">
				@lang('centurion::users.headings.listing')
			</h3>
			<div class="pull-right">
				@if (Sentinel::hasAccess('createusers'))
					<a class="btn btn-xs btn-default" href="{{ route('users.create') }}"><i class="fa-fw fas fa-plus"></i> @lang('centurion::users.buttons.add_new_user')</a>
				@endif
			</div>
		</div>	
		<div class="panel-body">
			@if($users && count($users) > 0)
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>@lang('centurion::users.headings.email_table_column')</th>
							<th>@lang('centurion::users.headings.first_name_table_column')</th>
							<th>@lang('centurion::users.headings.last_name_table_column')</th>
							<th>@lang('centurion::users.headings.status_table_column')</th>
							<th>@lang('centurion::generics.headings.actions_table_column')</th>
						</tr>
					</thead>
					<tbody>
					@foreach($users as $key => $user)
						<tr>
							<td>{{ $user->email }}</td>
							<td>{{ $user->first_name }}</td>
							<td>{{ $user->last_name }}</td>
							<td>
								@if (Activation::completed($user))
									<span class="label label-success">@lang('centurion::users.labels.user_status_active')</span>
								@elseif (!Activation::exists($user))
									<span class="label label-danger">@lang('centurion::users.labels.user_status_deactivated')</span>
								@else
									<span class="label label-default">@lang('centurion::users.labels.user_status_inactive')</span>
								@endif
							</td>

							<!-- we will also add show, edit, and delete buttons -->
							<td>
								@include('centurion::users/index_action_buttons', ['user' => $user])
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>

				@if (method_exists($users, 'links') 
					&& $users instanceof \Illuminate\Pagination\AbstractPaginator)
					{{ $users->links() }}
				@endif
			@else
				<strong>@lang('centurion::users.labels.empty_users')</strong>
			@endif
		</div>
	</div>
@endsection
{{-- Marks the end of the content for the section --}}