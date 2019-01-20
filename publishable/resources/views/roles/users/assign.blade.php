@extends('centurion::layouts/main_layout')

@section('centurion-title')
    @lang('centurion::roles.page_titles.assign_users')
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

    <form method="POST" action="{{ route('roles.users.sync', $role->id) }}">
        {{-- Good practice to include the below line to prevent security risks with forms --}}
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" />

        <!-- if there are errors, they will show here -->
        @component('centurion::components/errors')
            @slot('title')
                @lang('centurion::roles.headings.assign_users_validation_error')
            @endslot
            @slot('description')
                @lang('centurion::validation.statements.validation_error')
            @endslot
        @endcomponent

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">@lang('centurion::roles.headings.assign_users', ['name' => $role->name])</div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Start of List Containers -->
                        <div class="list-container">
                            <h3>@lang('centurion::roles.headings.users_assigned_for_role')</h3>
                            <div class="sortable">
                                <table class="table">
                                    <tbody id="assignedUsersList">
                                        @foreach($assignedUsers as $user)
                                            <tr class="item" data-id="{{ $user->id }}">
                                                <td>
                                                    <i class="fa-fw fas fa-user"></i>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-info" title="View" href="{{ route('users.show', $user->id) }}">
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
                            <h3>@lang('centurion::roles.headings.users_available_for_role')</h3>
                            <div class="sortable">
                                <table class="table">
                                    <tbody id="availableUsersList">
                                        @foreach($availableUsers as $user)
                                            <tr class="item" data-id="{{ $user->id }}">
                                                <td>
                                                    <i class="fa-fw fas fa-user"></i>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-info" title="View" href="{{ route('users.show', $user->id) }}">
                                                        <i class="fa-fw fas fa-folder-open"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <button type="button" title="Add" class="add-button btn btn-sm btn-success">
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
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-info">
                    <i class="fa-fw fas fa-ban"></i> @lang('centurion::generics.buttons.cancel')
                </a> 
            </div>
        </div>
    </form>

    <script>
        var availableUsersList = document.getElementById("availableUsersList");
        var availableUsersSortable = Sortable.create(availableUsersList, {
            animation: 200,
            group: "assignUsers",
            dataIdAttr: "data-id", // Lets you define the data id attribute to use
            onMove: function (evt, originalEvent) {
                var buttons = null;
                if (evt.to != evt.from)
                    buttons = changeAddButtonsToRemoveButtons(evt.dragged);
                else
                    buttons = changeRemoveButtonsToAddButtons(evt.dragged);
                $(buttons).tooltip('hide').tooltip('fixTitle'); // Updates the Bootstrap tool when the "title" attribute was changed
            },
            onSort: function (evt) {
                toggleEmptyClass(this, "empty-list");
            },
        });
        toggleEmptyClass(availableUsersSortable, "empty-list");

        var assignedUsersList = document.getElementById("assignedUsersList");
        var assignedUsersSortable = Sortable.create(assignedUsersList, {
            animation: 200,
            group: "assignUsers",
            dataIdAttr: "data-id", // Lets you define the data id attribute to use
            onMove: function (evt, originalEvent) {
                var buttons = null;
                if (evt.to != evt.from)
                    buttons = changeRemoveButtonsToAddButtons(evt.dragged);
                else
                    buttons = changeAddButtonsToRemoveButtons(evt.dragged);
                $(buttons).tooltip('hide').tooltip('fixTitle'); // Updates the Bootstrap tool when the "title" attribute was changed
            },
            onSort: function (evt) {
                toggleEmptyClass(this, "empty-list");
                updateInputs(this, "assigned_users");
            },
        });
        toggleEmptyClass(assignedUsersSortable, "empty-list");
        updateInputs(assignedUsersSortable, "assigned_users");

        $(availableUsersList).on('click', '.add-button', function(event){
            var $clickedButton = $(event.target);
            var $itemToMove = $clickedButton.closest("tr");
            var $targetList = $(assignedUsersList);

            var buttons = changeAddButtonsToRemoveButtons($itemToMove[0]);
            $(buttons).tooltip('hide').tooltip('fixTitle'); // Updates the Bootstrap tool when the "title" attribute was changed
            $targetList.append($itemToMove);
            toggleEmptyClass(availableUsersSortable, "empty-list");
            toggleEmptyClass(assignedUsersSortable, "empty-list");
            updateInputs(assignedUsersSortable, "assigned_users");
        });
        $(assignedUsersList).on('click', '.remove-button', function(event){
            var $clickedButton = $(event.target);
            var $itemToMove = $clickedButton.closest("tr");
            var $targetList = $(availableUsersList);
            
            var buttons = changeRemoveButtonsToAddButtons($itemToMove[0]);
            $(buttons).tooltip('hide').tooltip('fixTitle'); // Updates the Bootstrap tool when the "title" attribute was changed
            $targetList.append($itemToMove);
            toggleEmptyClass(availableUsersSortable, "empty-list");
            toggleEmptyClass(assignedUsersSortable, "empty-list");
            updateInputs(assignedUsersSortable, "assigned_users");
        });
    </script>
@endsection
{{-- Marks the end of the content for the section --}}