@extends('centurion::layouts/auth_layout')

@section('centurion-title')
    @lang('centurion::forgot_password.page_titles.reset_password')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/vertical-center.css') }}">
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
                            <h1><small>@lang('centurion::forgot_password.headings.reset_password')</small></h1>
                            <p>@lang('centurion::forgot_password.labels.about_reset_password')</p>
                            <div class="panel-body">
                                <form class="form" role="form" method="POST" action="{{ route('reset_password') }}">

                                    {{-- Good practice to include the below line to prevent security risks with forms --}}
                                    {{ csrf_field() }}

                                    <input type="hidden" name="user_id" value="{{ $userId }}">
                                    <input type="hidden" name="token" value="{{ $token }}">

									<div class="form-group">
										<div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa-fw fas fa-lock" id="password-addon"></i>
                                            </span>
											
											<input required type="password" name="password" id="password" value="{{ old('password') }}"
												class="form-control" placeholder="@lang('centurion::forgot_password.labels.password')" aria-describedby="password-addon"
												tabindex="1" />
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa-fw fas fa-lock" id="confirm-password-addon"></i>
                                            </span>
											<input required type="password" name="password_confirmation" id="password_confirmation"
												value="{{ old('password_confirmation') }}" class="form-control" placeholder="@lang('centurion::forgot_password.labels.confirm_password')"
												aria-describedby="confirm-password-addon" tabindex="2" />
										</div>
									</div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            @lang('centurion::forgot_password.buttons.reset_password')
                                        </button>
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