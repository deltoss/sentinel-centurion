<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>@lang('centurion::mails.activate_account.heading')</h1>
    <p>@lang('centurion::mails.generics.greeting', ['first_name' => $user->first_name, 'last_name' => $user->last_name])</p>
    <p>
        @lang('centurion::mails.activate_account.opener')
        <br />
        @lang('centurion::mails.activate_account.instruction')
    </p>
    <a href="{{ route('activate', ['email' => $user->email, 'activation' => $activation->code]) }}">
        @lang('centurion::mails.activate_account.activation_link')
    </a>
    <p>
        @lang('centurion::mails.activate_account.link_expiry_warning')
        <br />
        @lang('centurion::mails.activate_account.warning')
    </p>
</body>
</html>