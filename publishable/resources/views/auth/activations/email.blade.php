@extends('centurion::layouts/auth_layout')

@section('centurion-title')
    @lang('centurion::activation.page_titles.resend_activation_email')
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
                        @lang('centurion::activation.headings.resend_activation_email_validation_error')
                    @endslot
                    @slot('description')
                        @lang('centurion::validation.statements.validation_error')
                    @endslot
                @endcomponent

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3>
                                <i class="fa-fw fas fa-envelope" style="font-size: 4em;"></i>
                            </h3>
                            <h1><small>@lang('centurion::activation.headings.resend_activation_email')</small></h1>
                            <p>@lang('centurion::activation.labels.about_resend_activation_email')</p>
                            <div class="panel-body">
                                <form method="POST" role="form" action="{{ route('activate.resend') }}">
                                    {{-- Good practice to include the below line to prevent security risks with forms --}}
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa-fw fas fa-envelope"></i>
                                            </span>
                                            <input required id="email" name="email" placeholder="@lang('centurion::activation.labels.email')" class="form-control" type="email"
                                                value="{{ old('email') }}" tabindex="1" />
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
                                            @lang('centurion::activation.buttons.resend_activation_email')
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