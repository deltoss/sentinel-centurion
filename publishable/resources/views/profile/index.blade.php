@extends('centurion::layouts/main_layout')

@section('centurion-title')
	@lang('centurion::profile.page_titles.profile')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/decorative-icon-image.css') }}">	
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/information.css') }}">
@endsection

@section('centurion-content')
	{{-- We use "@parent" to avoid overwriting the section, and only appends to it. --}}
	@parent

    <!-- will be used to show any messages -->
	@component('centurion::components/message')
	@endcomponent

	@if($user)
		<div class="row">
			<div class="col-sm-4">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="text-center" style="color: #676a6c;">
							<h1>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h1>
							<div class="decorative-icon-image">
								<span class="fa-stack fa-4x">
									<i class="fas fa-circle fa-stack-2x"></i>
									<i class="fas fa-user fa-stack-1x fa-inverse"></i>
								</span>
							</div>
							<p>
								<strong>
								@if($userRoles && count($userRoles))
									<small>{{ $userRoles->sortBy("name")->implode('name', ', ') }}</small>
									<br />
								@endif
								{{ $user->email }}
								</strong>
							</p>
							<div class="text-center">
								<a class="btn btn-xs btn-warning" href="{{ route('profile.edit') }}">
									<i class="fa-fw fas fa-pencil-alt"></i> @lang('centurion::profile.buttons.update_profile')
								</a>
								<a class="btn btn-xs btn-success" href="{{ route('profile.change_password.request') }}">
									<i class="fa-fw fas fa-unlock-alt"></i> @lang('centurion::profile.buttons.change_password')
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="tabs-container info-tab-container">	
					<div class="panel panel-default">
						<div class="panel-body">
							<ul class="nav nav-pills" role="pilllist">
								<li class="active">
									<a href="#account" aria-controls="account" role="pill" data-toggle="pill">
										<i class="fa-fw fas fa-id-card"></i> @lang('centurion::profile.headings.account_information_tab')
									</a>
								</li>
								<li>
									<a href="#personal" aria-controls="personal" role="pill" data-toggle="pill">
										<i class="fa-fw fas fa-user"></i> @lang('centurion::profile.headings.personal_information_tab')
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="pill-content">
								<div role="pillpanel" class="pill-pane fade in active" id="account">
									<div class="info-group">
										<div class="info-label">
											<i class="info-icon fa-fw fas fa-envelope"></i>
											@lang('centurion::profile.labels.email')
										</div>
										<div class="info-value">
											{{ $user->email }}
										</div>
									</div>
									<div class="info-group">
										<div class="info-label">
											<i class="info-icon fa-fw fas fa-clock"></i>
											@lang('centurion::profile.labels.registered_since')
										</div>
										<div class="info-value">
											{{ date('jS F Y', strtotime($user->created_at)) }}
										</div>
									</div>
								</div>
								<div role="pillpanel" class="pill-pane fade" id="personal">
									<div class="info-group">
										<div class="info-label">
											<i class="info-icon fa-fw fas fa-feather"></i>
											@lang('centurion::profile.labels.first_name')
										</div>
										<div class="info-value">
											{{ $user->first_name }}
										</div>
									</div>

									<div class="info-group">
										<div class="info-label">
											<i class="info-icon fa-fw fas fa-feather"></i>
											@lang('centurion::profile.labels.last_name')
										</div>
										<div class="info-value">
											{{ $user->last_name }}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							@lang('centurion::profile.labels.time_with_us')
						</div>
					</div>
					<div class="panel-body">
						<ul class="icon-list">
							<li>								
								<i class="fa-fw fas fa-trophy"></i> @lang('centurion::profile.labels.registered_member_for_period', ['period' => \Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->diffForHumans(null, true)])
							</li>
							<li>
								<i class="fa-fw fas fa-clock"></i> @lang('centurion::profile.labels.registered_since_date', ['date' => date('jS F Y', strtotime($user->created_at))])
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							@lang('centurion::profile.headings.permissions')
						</div>
					</div>
					<div class="panel-body">
						<div class="panel-group" id="accordion">
							@foreach ($userRoles as $role)
								<div class="panel panel-info">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#{{ $role->slug }}-collapse">
												<i class="fa-fw fas fa-users"></i> {{ $role->name }}
											</a>
										</h4>
									</div>
									<div id="{{ $role->slug }}-collapse" class="panel-collapse collapse">
										<div class="panel-body">
											@if (count($role->abilities) > 0)
												<ul class="icon-list">
													@foreach ($role->abilities as $ability)
														<li>
															<span class="pull-right"> 
																@if($ability->permission->allowed)
																	<i class="fa-fw fas fa-check-circle" style="color: #1ab394;"></i>
																@else
																	<i class="fa-fw fas fa-times-circle" style="color: crimson;"></i>
																@endif
															</span>
															<span class="label label-info"><i class="fa-fw fas fa-cog"></i></span>&nbsp;&nbsp;{{ $ability->name }}
														</li>
													@endforeach
												</ul>
											@else
												<span class="label label-warning"><i class="fa-fw fas fa-exclamation-circle"></i></span>&nbsp;&nbsp;@lang('centurion::profile.labels.empty_role_permissions')
											@endif
										</div>
									</div>
								</div>						
							@endforeach
							<div class="panel panel-warning">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#special-user-permissions-collapse">
											<i class="fa-fw fas fa-user"></i> @lang('centurion::profile.headings.user_permissions')
										</a>
									</h4>
								</div>
								<div id="special-user-permissions-collapse" class="panel-collapse collapse">
									<div class="panel-body">
										@if (count($userAbilities) > 0)
											<ul class="icon-list">
												@foreach ($userAbilities as $ability)
													<li>
														<span class="pull-right"> 
															@if($ability->permission->allowed)
																<i class="fa-fw fas fa-check-circle" style="color: #1ab394;"></i>
															@else
																<i class="fa-fw fas fa-times-circle" style="color: crimson;"></i>
															@endif
														</span>
														<span class="label label-info"><i class="fa-fw fas fa-users"></i></span>&nbsp;&nbsp;{{ $ability->name }}
													</li>
												@endforeach
											</ul>
										@else
											<span class="label label-warning"><i class="fa-fw fas fa-exclamation-circle"></i></span>&nbsp;&nbsp;@lang('centurion::profile.labels.empty_permissions')
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
@endsection
{{-- Marks the end of the content for the section --}}