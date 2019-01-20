@extends('centurion::layouts/auth_layout')

@section('centurion-title')
	@lang('centurion::troubleshoot_login.page_titles.troubleshoot_login')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/divider.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/back-link.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/vertical-center.css') }}">
@endsection

@section('centurion-content')
<div class="vertical-center">
    <div class="container">
    	<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-primary">
                    <div class="panel-heading">
                    </div>
					<div class="panel-body">
                        <div class="text-center">
                            <h3>
                                <i class="fa-fw fas fa-question-circle fa-4x" style="color: #2e6da4;"></i>
                            </h3>
                            
                            <h1><small>@lang('centurion::troubleshoot_login.headings.troubleshoot_login')</small></h1>
                            <br />
                            <div class="row">
                                <div class="col-lg-12">
                                    <a style="width: 100%;" class="btn btn-primary" href="{{ route('forgot_password.request') }}" class="troubleshoot-login-link">
                                        @lang('centurion::troubleshoot_login.links.forgot_password')
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="divider" style="text-transform: uppercase;">
                                        @lang('centurion::generics.labels.or')
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a style="width: 100%;" class="btn btn-primary" href="{{ route('activate.resend.request') }}" class="troubleshoot-login-link">
                                        @lang('centurion::troubleshoot_login.links.resend_activation')
                                    </a>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
                <a class="back-link" href="{{ route('login.request') }}">
                    <i class="fa-fw fas fa-angle-left"></i>
                    @lang('centurion::troubleshoot_login.buttons.back')
                </a>
			</div>
		</div>
	</div>
</div>
@endsection
{{-- Marks the end of the content for the section --}}