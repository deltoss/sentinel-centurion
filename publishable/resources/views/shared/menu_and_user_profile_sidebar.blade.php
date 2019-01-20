<!--
User Profile Sidebar by @keenthemes
A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
Licensed under MIT
-->

<div class="profile-sidebar">
    <!-- SIDEBAR USERPIC -->
    <div class="profile-userpic">
        <i class="fa-fw fas fa-user img-responsive fa-10x"></i>
        <!-- ALTERNATIVELY WITH IMAGE 
            <img src="http://keenthemes.com/preview/metronic/theme/assets/admin/pages/media/profile/profile_user.jpg" class="img-responsive" alt=""> 
        -->
    </div>
    <!-- END SIDEBAR USERPIC -->
    <!-- SIDEBAR USER TITLE -->
    <div class="profile-usertitle">
        @if (Sentinel::getUser())
            <div class="profile-usertitle-name">
                {{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}
            </div>
            <div class="profile-usertitle-job">
                {{ Sentinel::getUser()->roles->sortBy("name")->implode('name', ', ') }}
            </div>
        @else
            <div class="profile-usertitle-name">
                @lang('centurion::menu.labels.welcome_guest')
            </div>
            <div class="profile-usertitle-job">
                @lang('centurion::menu.labels.no_roles')
            </div>
        @endif
    </div>
    <!-- END SIDEBAR USER TITLE -->
    <!-- SIDEBAR BUTTONS -->
    <div class="profile-userbuttons">
        @if (Sentinel::getUser())
            <a class="btn btn-success btn-sm" href="{{ route('profile.index') }}">
                <i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::menu.buttons.account')
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            <a class="btn btn-danger btn-sm" href="#"
                onclick="event.preventDefault(); $(this).prev('form').trigger('submit');">
                <i class="fa-fw fas fa-sign-out-alt"></i> @lang('centurion::menu.buttons.logout')
            </a>
        @else
            <a class="btn btn-info btn-sm" href="{{ route('login.request') }}">
                <i class="fa-fw fas fa-sign-in-alt"></i> @lang('centurion::menu.buttons.login')
            </a>
            @if (config('centurion.registration.enabled'))
            <a class="btn btn-success btn-sm" href="{{ route('register.request') }}">
                <i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::menu.buttons.register')
            </a>
            @endif
        @endif
    </div>
    <!-- END SIDEBAR BUTTONS -->
    <!-- SIDEBAR MENU -->
    <div class="profile-usermenu">
        <ul class="nav">
            @if (Sentinel::getUser())
                <li>
                    <a href="{{ route('profile.index') }}">
                        <i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::menu.menu_items.account')
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('users.index') }}">
                    <i class="fa-fw fas fa-user"></i> @lang('centurion::menu.menu_items.users')
                </a>
            </li>
            <li>
                <a href="{{ route('roles.index') }}">
                    <i class="fa-fw fas fa-users"></i> @lang('centurion::menu.menu_items.roles')
                </a>
            </li>
            <li>
                <a href="{{ route('abilities.index') }}">
                    <i class="fa-fw fas fa-th-list"></i> @lang('centurion::menu.menu_items.permissions')
                </a>
            </li>
        </ul>
    </div>
    <!-- END MENU -->
</div>