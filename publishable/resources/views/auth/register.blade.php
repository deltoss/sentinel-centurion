@extends('centurion::layouts/auth_layout')

@section('centurion-title')
	@lang('centurion::register.page_titles.register')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/login-or-register-form.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/vertical-center.css') }}">
    @if (config('captcha.secret') && config('captcha.sitekey'))
        {!! NoCaptcha::renderJs(App::getLocale()) !!}
    @endif
@endsection

@section('centurion-content')
<div class="vertical-center">
    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
                <!-- if there are errors, they will show here -->
                @component('centurion::components/errors')
                    @slot('title')
                        @lang('centurion::register.headings.register_validation_error')
                    @endslot
                    @slot('description')
                        @lang('centurion::validation.statements.validation_error')
                    @endslot
                @endcomponent
				
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="{{ route('login.request') }}" id="login-form-link">
									@lang('centurion::login.headings.login')
								</a>
							</div>
							<div class="col-xs-6">
								<a href="#" class="active" id="register-form-link">
									@lang('centurion::register.headings.register')
								</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
                                <form id="register-form" role="form" method="POST" action="{{ route('register') }}">               
									{{-- Good practice to include the below line to prevent security risks with forms --}}
									{{ csrf_field() }}

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" id="register-email-addon">@</span>
											<input required type="text" name="email" id="email" value="{{ old('email') }}"
												class="form-control" placeholder="@lang('centurion::register.labels.email')" aria-describedby="register-email-addon" 
												tabindex="1"/>
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa-fw fas fa-pencil-alt" id="register-first-name-addon"></i>
											</span>
											<input required type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
												class="form-control" placeholder="@lang('centurion::register.labels.first_name')" aria-describedby="register-first-name-addon"
												tabindex="2" />
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa-fw fas fa-pencil-alt" id="register-last-name-addon"></i>
											</span>
											<input required type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
												class="form-control" placeholder="@lang('centurion::register.labels.last_name')" aria-describedby="register-last-name-addon"
												tabindex="3" />
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa-fw fas fa-lock" id="register-password-addon"></i>
											</span>
											<input required type="password" name="password" id="password" value="{{ old('password') }}"
												class="form-control" placeholder="@lang('centurion::register.labels.password')" aria-describedby="register-password-addon"
												tabindex="4" />
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa-fw fas fa-lock" id="register-confirm-password-addon"></i>
											</span>
											<input required type="password" name="password_confirmation" id="password_confirmation"
												value="{{ old('password_confirmation') }}" class="form-control" placeholder="@lang('centurion::register.labels.confirm_password')"
												aria-describedby="register-confirm-password-addon" tabindex="5" />
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
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<button type="submit" class="form-control btn btn-register" tabindex="6">
													@lang('centurion::register.buttons.register')
												</button>
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