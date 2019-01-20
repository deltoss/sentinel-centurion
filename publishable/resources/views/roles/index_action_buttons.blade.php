<div style="white-space: nowrap;">
    <a class="btn btn-sm btn-default" href="{{ route('roles.show', $role->id) }}">
        <i class="fa-fw far fa-folder"></i> @lang('centurion::generics.buttons.view')
    </a>
    
    @if (Sentinel::hasAccess('editroles'))
        <a class="btn btn-sm btn-default" href="{{ route('roles.edit', $role->id) }}">
            <i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::generics.buttons.edit')
        </a>
    @endif

    @if (Sentinel::hasAnyAccess('editroles', 'deleteroles'))
        <a id="{{ 'moreBtn' . $role->id }}" tabindex="-1" class="btn btn-sm btn-default" role="button"
            data-toggle="button-popover">
            <i class="fa-fw fas fa-ellipsis-v"></i> @lang('centurion::generics.buttons.more')
        </a>

        <script type="text/template" class="popover-html-content">
            @if (Sentinel::hasAccess('editroles'))
                <a class="btn btn-sm btn-default" href="{{ route('roles.users.assign', $role->id) }}">
                    <i class="fa-fw fas fa-user"></i> @lang('centurion::roles.buttons.edit_role_users')
                </a>

                <a class="btn btn-sm btn-default" href="{{ route('roles.abilities.assign', $role->id) }}">
                    <i class="fa-fw fas fa-th-list"></i> @lang('centurion::roles.buttons.edit_role_permissions')
                </a>
            @endif

            @if (Sentinel::hasAccess('deleteroles'))
                <!-- delete the role (uses the destroy method DESTROY /Roles/{id} -->
                <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE" />
                    <a type="submit" onclick="return confirmDelete(event, this)" class="btn btn-sm btn-default">
                        <i class="fa-fw far fa-trash-alt"></i> @lang('centurion::generics.buttons.delete')
                    </a>
                </form>
            @endif
        </script>
        <script>
            $(document).ready(function(){
                initializeButtonPopover($("#{{ 'moreBtn' . $role->id }}"));
            });
        </script>
    @endif
</div>