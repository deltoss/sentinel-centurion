<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>@lang('centurion::mails.forgot_password.heading')</h1>
    <p>
        @lang('centurion::mails.forgot_password.opener')
        <br />
        @lang('centurion::mails.forgot_password.instruction')
    </p>
    <p>
        <a href="{{ route('reset_password.request', ['userId' => $user->id, 'token' => $reminder->code]) }}">
            @lang('centurion::mails.forgot_password.reset_password_link')
        </a>
    </p>
    <p>
        @lang('centurion::mails.forgot_password.link_expiry_warning')
        <br />
        @lang('centurion::mails.forgot_password.warning')
    </p>
</body>
</html>