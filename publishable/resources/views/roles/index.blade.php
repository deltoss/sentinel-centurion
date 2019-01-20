@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::roles.page_titles.listing')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/popover.css') }}">
	<script src="{{ asset('vendor/centurion/js/popover.js') }}"></script>
	<script>
		function confirmDelete(event, element) {
            swal({
				type: "warning",
    			showCancelButton: true,
				title: "@lang('centurion::roles.headings.delete_role_confirmation')",
				text: "@lang('centurion::roles.labels.delete_role_confirmation')",
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
			@lang('centurion::roles.headings.listing_requested_action_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.requested_action_error')
		@endslot
	@endcomponent

	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h3 class="panel-title pull-left">
				@lang('centurion::roles.headings.listing')
			</h3>
			<div class="pull-right">
				@if (Sentinel::hasAccess('createroles'))
					<a class="btn btn-xs btn-default" href="{{ route('roles.create') }}"><i class="fa-fw fas fa-plus"></i> @lang('centurion::roles.buttons.add_new_role')</a>
				@endif
			</div>
		</div>
		<div class="panel-body">
			@if($roles && count($roles) > 0)
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>@lang('centurion::roles.headings.name_table_column')</th>
							<th>@lang('centurion::roles.headings.slug_table_column')</th>
							<th>@lang('centurion::roles.headings.number_of_members_table_column')</th>
							<th>@lang('centurion::generics.headings.actions_table_column')</th>
						</tr>
					</thead>
					<tbody>
					@foreach($roles as $role)
						<tr>
							<td>{{ $role->name }}</td>
							<td>{{ $role->slug }}</td>
							<td>{{ $role->users->count() }}</td>

							<!-- we will also add show, edit, and delete buttons -->
							<td>
								@include('centurion::roles/index_action_buttons', ['role' => $role])
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>

				@if (method_exists($roles, 'links') 
					&& $roles instanceof \Illuminate\Pagination\AbstractPaginator)
					{{ $roles->links() }}
				@endif
			@else
				<strong>@lang('centurion::roles.labels.empty_roles')</strong>
			@endif
		</div>
	</div>
@endsection
{{-- Marks the end of the content for the section --}}