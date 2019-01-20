@extends('centurion::layouts/auth_layout')

@section('centurion-title')
	@lang('centurion::login.page_titles.login')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/login-or-register-form.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/vertical-center.css') }}">
@endsection

@section('centurion-content')
<div class="vertical-center">
    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
                <!-- if there are errors, they will show here -->
                @component('centurion::components/errors')
                    @slot('title')
                        @lang('centurion::login.headings.login_validation_error')
                    @endslot
                @endcomponent

				<!-- 
					will be used to show any messages,
					i.e. after registration or activation
				-->
				@component('centurion::components/message')
				@endcomponent

				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							@if (config('centurion.registration.enabled'))
								<div class="col-xs-6">
									<a href="#" class="active" id="login-form-link">@lang('centurion::login.headings.login')</a>
								</div>
								<div class="col-xs-6">
									<a href="{{ route('register.request') }}" id="register-form-link">
										@lang('centurion::register.headings.register')
									</a>
								</div>
							@else
								<div class="col-xs-12">
									<a href="#" class="active" id="login-form-link">@lang('centurion::login.headings.login')</a>
								</div>
							@endif
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
                                <form id="login-form" method="POST" role="form" action="{{ route('login') }}">
									{{-- Good practice to include the below line to prevent security risks with forms --}}
									{{ csrf_field() }}

									{{-- 
										Our authentication middleware sets up automatic redirect after logging in.
										
										To pass to the controller on form submission, we need to store the 'redirectUrl'
										variable into a hidden input.
									--}}
									<input id="redirectUrl" name="redirectUrl" type="hidden" value="{{ app('request')->input('redirectUrl') }}" />

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" id="login-addon">@</span>
											<input required type="text" name="login" id="login" value="{{ old('login') }}"
												class="form-control" placeholder="@lang('centurion::login.labels.login')" aria-describedby="login-login-addon" 
												tabindex="1"/>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa-fw fas fa-lock" id="login-password-addon"></i>
											</span>
											<input required type="password" name="password" id="password" value="{{ old('password') }}"
												class="form-control" placeholder="@lang('centurion::login.labels.password')" aria-describedby="login-password-addon"
												tabindex="2" />
										</div>
									</div>
									<div class="form-group">
										<div class="text-center">
											<input type="checkbox" tabindex="3" name="remember" id="remember">
											<label for="remember"> @lang('centurion::login.labels.remember_me')</label>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<button type="submit" class="form-control btn btn-login" tabindex="4">
													@lang('centurion::login.buttons.login')
												</button>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
												<div class="text-center">
													<a href="{{ route('login.troubleshoot') }}" tabindex="5" class="login-troubleshoot">
														@lang('centurion::login.links.troubleshoot_login')
													</a>
												</div>
											</div>
										</div>
									</div>
                                </form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
{{-- Marks the end of the content for the section --}}