@extends('centurion::layouts/auth_layout')

@section('centurion-title')
	@lang('centurion::register.page_titles.registration_email_sent')
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
			<div class="col-md-6 col-md-offset-3">
                <!-- if there are errors, they will show here -->
                @component('centurion::components/errors')
                    @slot('title')
                        @lang('centurion::register.headings.registration_email_sent_validation_error')
                    @endslot
                    @slot('description')
                        @lang('centurion::validation.statements.validation_error')
                    @endslot
                @endcomponent

				<div class="panel panel-primary">
					<div class="panel-heading">
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="text-center">
									<h3>
										<i class="fa-fw fas fa-pencil-alt" style="font-size: 4em; color: royalblue;"></i>
									</h3>
									<h1><small>@lang('centurion::register.headings.registration_email_sent')</small></h1>
									<p>
										@lang('centurion::register.labels.registration_email_sent', ['email' => $email ])
									</p>
									<p>
										@lang('centurion::register.labels.registration_email_not_sent_instructions')
									</p>
									<br />
									<form method="POST" role="form" action="{{ route('activate.resend') }}">
										{{-- Good practice to include the below line to prevent security risks with forms --}}
										{{ csrf_field() }}
										<input type="hidden" name="email" id="email" value="{{ $email }}" />

										<div class="form-group">
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<button type="submit" class="btn btn-primary">
														@lang('centurion::register.buttons.resend_activation_email')
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
</div>
@endsection
{{-- Marks the end of the content for the section --}}