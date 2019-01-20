<div style="white-space: nowrap;">
    <a class="btn btn-sm btn-default" href="{{ route('abilities.show', $ability->id) }}">
        <i class="fa-fw far fa-folder"></i> @lang('centurion::generics.buttons.view')
    </a>

    @if (Sentinel::hasAccess('editpermissions'))
        <a class="btn btn-sm btn-default" href="{{ route('abilities.edit', $ability->id) }}">
            <i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::generics.buttons.edit')
        </a>
    @endif

    @if (Sentinel::hasAnyAccess('editpermissions', 'deletepermissions'))
        <a id="{{ 'moreBtn' . $ability->id }}" tabindex="-1" class="btn btn-sm btn-default" role="button"
            data-toggle="button-popover">
            <i class="fa-fw fas fa-ellipsis-v"></i> @lang('centurion::generics.buttons.more')
        </a>
        
        <script type="text/template" class="popover-html-content">
            @if (Sentinel::hasAccess('editpermissions'))
                <a class="btn btn-sm btn-default" href="{{ route('abilities.users.assign', $ability->id) }}">
                    <i class="fa-fw fas fa-user"></i> @lang('centurion::permissions.buttons.edit_permission_users')
                </a>

                <a class="btn btn-sm btn-default" href="{{ route('abilities.roles.assign', $ability->id) }}">
                    <i class="fa-fw fas fa-users"></i> @lang('centurion::permissions.buttons.edit_permission_roles')
                </a>
            @endif

            @if (Sentinel::hasAccess('deletepermissions'))
                <form method="POST" action="{{ route('abilities.destroy', $ability->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE" />
                    <a href="#" type="submit" onclick="return confirmDelete(event, this)" class="btn btn-sm btn-default">
                        <i class="fa-fw far fa-trash-alt"></i> @lang('centurion::generics.buttons.delete')
                    </button>
                </form>
            @endif
        </script>

        <script>
            $(document).ready(function(){
                initializeButtonPopover($("#{{ 'moreBtn' . $ability->id }}"));
            });
        </script>
    @endif
</div>