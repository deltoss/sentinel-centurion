@extends('centurion::layouts/main_layout')

@section('centurion-title')
    @lang('centurion::permissions.page_titles.assign_users')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/checkbox-list.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/tristate-checkbox.css') }}">
	<script src="{{ asset('vendor/centurion/js/tristate-checkbox-list.js') }}"></script>
	<script>
		$(document).ready(function(){
			$(".tristate").each(function(index, element){
				var $checkedRadioElement = $(element).find(":radio:checked");
				if ($checkedRadioElement && $checkedRadioElement.length > 0)
					updateTristateInputs(element, $checkedRadioElement[0]);
				
				var radios = $(element).find(":radio");
				radios.on("change", function() {
					updateTristateInputs(element, this);
				});
			});
		});
	</script>
@endsection

@section('centurion-content')
	{{-- We use "@parent" to avoid overwriting the section, and only appends to it. --}}
	@parent

    <form method="POST" action="{{ route('abilities.users.sync', $ability->id) }}">
        {{-- Good practice to include the below line to prevent security risks with forms --}}
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" />

        <!-- if there are errors, they will show here -->
        @component('centurion::components/errors')
            @slot('title')
                @lang('centurion::permissions.headings.assign_users_validation_error')
            @endslot
            @slot('description')
                @lang('centurion::validation.statements.validation_error')
            @endslot
        @endcomponent

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">@lang('centurion::permissions.headings.assign_users', ['name' => $ability->name])</div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Start of Checkboxes -->
                        <div class="checkbox-list">
                            @foreach($userPermissions as $userPermission)
                                <span class="tristate tristate-accept-deny-checkbox">
                                    <input type="radio" class="deny-checkbox" id="{{ 'userCheckBox' . $userPermission['user']->id }}-state-off" data-tristate-name="denied_users[]" value="{{ $userPermission['user']->id }}"
                                        @if ($userPermission['allowed'] == '0')
                                            checked="checked"
                                        @endif
                                    />
                                    <input type="radio" class="null-checkbox" id="{{ 'userCheckBox' . $userPermission['user']->id }}-state-null" data-tristate-name="null_users[]" value="{{ $userPermission['user']->id }}"
                                        @if ($userPermission['allowed'] == '')
                                            checked="checked"
                                        @endif
                                    />
                                    <input type="radio" class="accept-checkbox" id="{{ 'userCheckBox' . $userPermission['user']->id }}-state-on" data-tristate-name="accepted_users[]" value="{{ $userPermission['user']->id }}"
                                        @if ($userPermission['allowed'] == '1')
                                            checked="checked"
                                        @endif
                                    />
                                    <i></i>
                                    <label for="{{ 'userCheckBox' . $userPermission['user']->id }}-state-off">{{ $userPermission['user']->email }}</label>
                                    <label for="{{ 'userCheckBox' . $userPermission['user']->id }}-state-null">{{ $userPermission['user']->email }}</label>
                                    <label for="{{ 'userCheckBox' . $userPermission['user']->id }}-state-on">{{ $userPermission['user']->email }}</label>
                                </span>
                            @endforeach
                        </div>
                        <!-- End of Checkboxes -->
                    </div>
                </div>
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