@extends('centurion::layouts/auth_layout')

@section('centurion-title')
	@lang('centurion::activation.page_titles.activation_email_resent')
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
				<div class="panel panel-primary">
					<div class="panel-heading">
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="text-center">
									<h3>
										<i class="fa-fw fas fa-paper-plane fa-4x" style="color: royalblue;"></i>
									</h3>
									<h1><small>@lang('centurion::activation.headings.activation_email_resent')</small></h1>
									<p>
										@lang('centurion::activation.labels.activation_email_resent', ['email' => $email])
									</p>
									<div class="row">
										<div class="col-sm-6 col-sm-offset-3">
											<a class="btn btn-primary" href="{{ route('login.request') }}">
												@lang('centurion::generics.buttons.sign_in_now')
											</a>
										</div>
									</div>
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