<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>@lang('centurion::mails.account_created.heading')</h1>
    <p>@lang('centurion::mails.generics.greeting', ['first_name' => $user->first_name, 'last_name' => $user->last_name])</p>
    <p>
        @lang('centurion::mails.account_created.opener')
        <br />
        @lang('centurion::mails.account_created.instruction')
    </p>
    @if ($user->change_password_on_activation)
        <a href="{{ route('activate_with_password.request', ['email' => $user->email, 'activation' => $activation->code]) }}">
            @lang('centurion::mails.account_created.activation_link')
        </a>
    @else
        <a href="{{ route('activate', ['email' => $user->email, 'activation' => $activation->code]) }}">
            @lang('centurion::mails.activate_account.activation_link')
        </a>
    @endif
    <p>
        @lang('centurion::mails.account_created.link_expiry_warning')
        <br />
        @lang('centurion::mails.account_created.warning')
    </p>
</body>
</html>