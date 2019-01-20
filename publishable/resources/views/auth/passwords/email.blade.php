@extends('centurion::layouts/auth_layout')

@section('centurion-title')
    @lang('centurion::forgot_password.page_titles.forgot_password')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/back-link.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/vertical-center.css') }}">
    @if (config('captcha.secret') && config('captcha.sitekey'))
        {!! NoCaptcha::renderJs(App::getLocale()) !!}
    @endif
@endsection

@section('centurion-content')
<div class="vertical-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <!-- if there are errors, they will show here -->
                @component('centurion::components/errors')
                    @slot('title')
                        @lang('centurion::forgot_password.headings.forgot_password_validation_error')
                    @endslot
                    @slot('description')
                        @lang('centurion::validation.statements.validation_error')
                    @endslot
                @endcomponent

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3>
                                <i class="fa-fw fas fa-lock fa-4x"></i>
                            </h3>
                            <h1><small>@lang('centurion::forgot_password.headings.forgot_password')</small></h1>
                            <p>@lang('centurion::forgot_password.labels.about_forgot_password')</p>
                            <div class="panel-body">
                                <form class="form" role="form" method="POST" action="{{ route('forgot_password.email') }}">
                                    {{-- Good practice to include the below line to prevent security risks with forms --}}
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa-fw fas fa-envelope"></i>
                                            </span>
                                            <input required id="email" name="email" placeholder="@lang('centurion::forgot_password.labels.email')" class="form-control" type="email"
                                                value="{{ old('email') }}" />
                                        </div>
                                    </div>
									@if (config('captcha.secret') && config('captcha.sitekey'))
										<div class="form-group">
											<div class="input-group" style="width:0; margin: 0 auto;">
												{!! NoCaptcha::display() !!}
											</div>
										</div>
									@endif
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            @lang('centurion::forgot_password.buttons.request_password_recovery')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="back-link" href="{{ route('login.troubleshoot') }}">
                    <i class="fa-fw fas fa-angle-left"></i> 
                    @lang('centurion::generics.buttons.back')
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- Marks the end of the content for the section --}}