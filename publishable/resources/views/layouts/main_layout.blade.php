@extends('centurion::layouts/layout')

@section('title')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
    @parent

    @yield('centurion-title')
@endsection

@section('head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
    @parent
    
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/centurion/css/menu-and-user-profile-sidebar.css') }}">

    <style>
        body {
            padding-top: 70px;
        }
    </style>

    @yield('centurion-head')
@endsection

@section('body')
    <div class="container-fluid"> 
        <div class="row">
            @include('centurion::shared/header')
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body sidebar-wrapper">
                        @include('centurion::shared/menu_and_user_profile_sidebar')
                    </div>
                </div>
            </div>
            <div class="col-md-9">              
				@yield('centurion-content', 'Section "Content" has not been defined')
            </div>
        </div>
    </div>
@endsection
{{-- Marks the end of the content for the section --}}