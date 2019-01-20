@extends('centurion::layouts/auth_layout')

@section('centurion-title')
    @lang('centurion::authorisation.page_titles.unauthorised')
@endsection

@section('centurion-head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
	@parent

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/vertical-center.css') }}">

    <script src="{{ asset('vendor/centurion/js/count-down.js') }}"></script>
    <script>
        window.onload = function () {
            countDown(document.getElementById("timer"), '{{ url("/") }}');
        }
    </script>
@endsection

@section('centurion-content')
<div class="vertical-center">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="panel-title">
                            @lang('centurion::authorisation.headings.unauthorised')
                        </div>
                    </div>
                    <div class="panel-body" style="color: #cc3431;">
                        <p>
                            @lang('centurion::authorisation.labels.unauthorised')
                        </p>
                        <p>
                            {!!
                                trans('centurion::authorisation.links.redirection_notice', [
                                    'redirect_to' => 
                                        '<a style="color: #00A2A3" href="' . url('/') . '">'
                                        . trans('centurion::authorisation.links.redirect_to')
                                        . '</a>',
                                    'time_in_seconds' => '<span id="timer">8</span>'
                                ])
                            !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- Marks the end of the content for the section --}}