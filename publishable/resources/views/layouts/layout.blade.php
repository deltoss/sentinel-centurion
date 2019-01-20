<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 
            For JQuery AJAX to function correctly with Laravel, and support CSRF Tokens.
            For more information, see:
              https://laravel.com/docs/5.7/csrf#csrf-x-csrf-token
        -->
        <meta name="csrf-token" content="{{ csrf_token() }}"> 

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <link rel="stylesheet" href="{{ asset('/vendor/centurion/plugins/bootstrap/css/bootstrap.min.css') }}" />
		<link rel="stylesheet" href="{{ asset('/vendor/centurion/plugins/fontawesome/css/all.min.css') }}" />
        <script src="{{ asset('/vendor/centurion/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('/vendor/centurion/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('/vendor/centurion/plugins/sweetalert2/sweetalert2.min.css') }}" />
        <script src="{{ asset('/vendor/centurion/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

        <script>
            $.ajaxSetup({ // For JQuery AJAX to function correctly with Laravel, and support CSRF Tokens
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $(document).ready(function(){    
                // Applies Bootstrap tooltip to
                // all elements with title, as well
                // as any newly created element.
                $('body').tooltip({
                    selector: '[title]'
                });
            });
        </script>

        @yield('head')
    </head>
    <body>
        @yield('body', 'Section "body" has not been defined')
    </body>
</html>