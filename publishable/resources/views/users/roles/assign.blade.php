@extends('centurion::layouts/main_layout')

@section('centurion-title')
    @lang('centurion::users.page_titles.assign_roles')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/user-roles-sortable.css') }}">
	<script src="{{ asset('vendor/centurion/js/user-roles-sortable.js') }}"></script>

    <script src="{{ asset('/vendor/centurion/plugins/Sortable/Sortable.js') }}"></script>
	<!--
		We use the development version, i.e. the unminified version
		due to this bug:
			https://stackoverflow.com/questions/48804134/rubaxa-sortable-failed-to-execute-matches-on-element-is-not-a-valid-se
	-->
@endsection

@section('centurion-content')
    {{-- We use "@parent" to avoid overwriting the section, and only appends to it. --}}
	@parent

    <form method="POST" action="{{ route('users.roles.sync', $user->id) }}">
        {{-- Good practice to include the below line to prevent security risks with forms --}}
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" />

        <!-- if there are errors, they will show here -->
        @component('centurion::components/errors')
            @slot('title')
                @lang('centurion::users.headings.assign_roles_validation_error')
            @endslot
            @slot('description')
                @lang('centurion::validation.statements.validation_error')
            @endslot
        @endcomponent

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">@lang('centurion::users.headings.assign_roles', ['first_name' => $user->first_name, 'last_name' => $user->last_name])</div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Start of List Containers -->
                        <div class="list-container">
                            <h3>@lang('centurion::users.headings.roles_assigned_for_user')</h3>
                            <div class="sortable">
                                <table class="table">
                                    <tbody id="assignedRolesList">
                                        @foreach($assignedRoles as $role)
                                            <tr class="item" data-id="{{ $role->id }}">
                                                <td>
                                                    <i class="fa-fw fas fa-user"></i>
                                                    {{ $role->name }}
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-info" title="View" href="{{ route('roles.show', $role->id) }}">
                                                        <i class="fa-fw fas fa-folder-open"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <button type="button" title="Remove" class="remove-button btn btn-sm btn-danger">
                                                        <i class="fa-fw fas fa-minus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="list-container">
                            <h3>@lang('centurion::users.headings.roles_available_for_user')</h3>
                            <div class="sortable">
                                <table class="table">
                                    <tbody id="availableRolesList">
                                        @foreach($availableRoles as $role)
                                            <tr class="item" data-id="{{ $role->id }}">
                                                <td>
                                                    <i class="fa-fw fas fa-user"></i>
                                                    {{ $role->name }}
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-info" title="@lang('centurion::generics.buttons.view')" href="{{ route('roles.show', $role->id) }}">
                                                        <i class="fa-fw fas fa-folder-open"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <button type="button" title="@lang('centurion::generics.buttons.add')" class="add-button btn btn-sm btn-success">
                                                        <i class="fa-fw fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End of List Containers -->
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-warning">
                    <i class="fa-fw fas fa-sync-alt"></i> @lang('centurion::generics.buttons.update')
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-info">
                    <i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
                </a> 
            </div>
        </div>
    </form>

    <script>
        var availableRolesList = document.getElementById("availableRolesList");
        var availableRolesSortable = Sortable.create(availableRolesList, {
            animation: 200,
            group: "assignRoles",
            dataIdAttr: "data-id", // Lets you define the data id attribute to use
            onMove: function (evt, originalEvent) {
                var buttons = null;
                if (evt.to != evt.from)
                    buttons = changeAddButtonsToRemoveButtons(evt.dragged);
                else
                    buttons = changeRemoveButtonsToAddButtons(evt.dragged);
                $(buttons).tooltip('fixTitle');
            },
            onSort: function (evt) {
                toggleEmptyClass(this, "empty-list");
            },
        });
        toggleEmptyClass(availableRolesSortable, "empty-list");

        var assignedRolesList = document.getElementById("assignedRolesList");
        var assignedRolesSortable = Sortable.create(assignedRolesList, {
            animation: 200,
            group: "assignRoles",
            dataIdAttr: "data-id", // Lets you define the data id attribute to use
            onMove: function (evt, originalEvent) {
                var buttons = null;
                if (evt.to != evt.from)
                    buttons = changeRemoveButtonsToAddButtons(evt.dragged);
                else
                    buttons = changeAddButtonsToRemoveButtons(evt.dragged);
                $(buttons).tooltip('fixTitle');
            },
            onSort: function (evt) {
                toggleEmptyClass(this, "empty-list");
                updateInputs(this, "assigned_roles");
            },
        });
        toggleEmptyClass(assignedRolesSortable, "empty-list");
        updateInputs(assignedRolesSortable, "assigned_roles");

        $(availableRolesList).on('click', '.add-button', function(event){
            var $clickedButton = $(event.target);
            var $itemToMove = $clickedButton.closest("tr");
            var $targetList = $(assignedRolesList);

            var buttons = changeAddButtonsToRemoveButtons($itemToMove[0]);
            $(buttons).tooltip('hide').tooltip('fixTitle'); // Updates the Bootstrap tool when the "title" attribute was changed
            $targetList.append($itemToMove);
            toggleEmptyClass(availableRolesSortable, "empty-list");
            toggleEmptyClass(assignedRolesSortable, "empty-list");
            updateInputs(assignedRolesSortable, "assigned_roles");
        });
        $(assignedRolesList).on('click', '.remove-button', function(event){
            var $clickedButton = $(event.target);
            var $itemToMove = $clickedButton.closest("tr");
            var $targetList = $(availableRolesList);
            
            var buttons = changeRemoveButtonsToAddButtons($itemToMove[0]);
            $(buttons).tooltip('hide').tooltip('fixTitle'); // Updates the Bootstrap tool when the "title" attribute was changed
            $targetList.append($itemToMove);
            toggleEmptyClass(availableRolesSortable, "empty-list");
            toggleEmptyClass(assignedRolesSortable, "empty-list");
            updateInputs(assignedRolesSortable, "assigned_roles");
        });
    </script>
@endsection
{{-- Marks the end of the content for the section --}}