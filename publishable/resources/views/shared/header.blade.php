<nav class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-header">
        <div class="navbar-brand">
            <i class="fa-fw fab fa-fort-awesome" style="color: limegreen;"></i>
            @lang('centurion::header.labels.application_name')
        </div>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">@lang('centurion::header.buttons.toggle_navigation')</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right" style="padding-right: 20px;">
            @if (Sentinel::getUser())
                <li class="page-scroll">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    <a href="#" onclick="event.preventDefault(); $(this).prev('form').trigger('submit');">
                        <i class="fa-fw fas fa-sign-out-alt"></i> @lang('centurion::header.menu_items.logout')
                    </a>
                </li>
            @else
                <li class="page-scroll">
                    <a href="{{ route('login.request') }}">
                        <i class="fa-fw fas fa-sign-in-alt"></i> @lang('centurion::header.menu_items.login')
                    </a>
                </li>
                @if (config('centurion.registration.enabled'))
                <li class="page-scroll">
                    <a href="{{ route('register.request') }}">
                        <i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::header.menu_items.register')
                    </a>
                </li>
                @endif
            @endif
        </ul>
    </div><!--/.nav-collapse -->
</nav>








