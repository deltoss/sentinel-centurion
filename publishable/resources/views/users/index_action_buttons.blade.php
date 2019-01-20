<div style="white-space: nowrap;">
    <a class="btn btn-sm btn-default" href="{{ route('users.show', $user->id) }}">
        <i class="fa-fw far fa-folder"></i> @lang('centurion::generics.buttons.view')
    </a>

    @if (Sentinel::hasAccess('editusers'))
        <a class="btn btn-sm btn-default" href="{{ route('users.edit', $user->id) }}">
            <i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::generics.buttons.edit')
        </a>
    @endif

    @if (Sentinel::hasAnyAccess('editusers', 'deleteusers'))
        <a id="{{ 'moreBtn' . $user->id }}" tabindex="-1" class="btn btn-sm btn-default"
            role="button" data-toggle="button-popover">
            <i class="fa-fw fas fa-ellipsis-v"></i> @lang('centurion::generics.buttons.more')
        </a>

        <script type="text/template" class="popover-html-content">
            @if (Sentinel::hasAccess('editusers'))
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
                            <i class="fa-fw fas fa-envelope"></i> @lang('centurion::users.buttons.send_user_reset_password_email')
                        </button>
                    </form>
                @endif
            @endif

            @if (Sentinel::hasAccess('deleteusers'))
                <!-- delete the user (uses the destroy method DESTROY /Users/{id} -->
                <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" onclick="return confirmDelete(event, this)" class="btn btn-sm btn-default">
                        <i class="fa-fw far fa-trash-alt"></i> @lang('centurion::generics.buttons.delete')
                    </button>
                </form>
            @endif
        </script>
        <script>
            $(document).ready(function(){
                initializeButtonPopover($("#{{ 'moreBtn' . $user->id }}"));
            });
        </script>
    @endif
</div>