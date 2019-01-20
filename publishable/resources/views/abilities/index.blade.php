@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::permissions.page_titles.listing')
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

    <!-- will be used to show any messages -->
	@component('centurion::components/message')
	@endcomponent

	<!-- if there are errors, they will show here -->
	@component('centurion::components/errors')
		@slot('title')
			@lang('centurion::permissions.headings.listing_requested_action_error')
		@endslot
		@slot('description')
			@lang('centurion::validation.statements.requested_action_error')
		@endslot
	@endcomponent

	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<div class="panel-title pull-left">
				@lang('centurion::permissions.headings.listing')
			</div>
			<div class="pull-right">
				@if (Sentinel::hasAccess('createpermissions'))
					<a class="btn btn-xs btn-default" href="{{ route('abilities.create') }}"><i class="fa-fw fas fa-plus"></i> @lang('centurion::permissions.buttons.add_new_permission')</a>
				@endif
			</div>
		</div>
		<div class="panel-body">
			@if($abilities && count($abilities) > 0)
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>@lang('centurion::permissions.headings.name_table_column')</th>
							<th>@lang('centurion::permissions.headings.slug_table_column')</th>
							<th>@lang('centurion::permissions.headings.number_of_members_table_column')</th>
							<th>@lang('centurion::permissions.headings.category_table_column')</th>
							<th>@lang('centurion::generics.headings.actions_table_column')</th>
						</tr>
					</thead>
					<tbody>
					@foreach($abilities as $key => $ability)
						<tr>
							<td>{{ $ability->name }}</td>
							<td>{{ $ability->slug }}</td>
							<td>{{ $ability->getAllAllowedUsers()->count() }}</td>
							<td>{{ $ability->abilityCategory->name }}</td>

							<!-- we will also add show, edit, and delete buttons -->
							<td>
								@include('centurion::abilities/index_action_buttons', ['ability' => $ability])
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>

				@if (method_exists($abilities, 'links') 
					&& $abilities instanceof \Illuminate\Pagination\AbstractPaginator)
					{{ $abilities->links() }}
				@endif
			@else
				<strong>@lang('centurion::permissions.labels.empty_permissions')</strong>
			@endif
		</div>
	</div>
@endsection
{{-- Marks the end of the content for the section --}}