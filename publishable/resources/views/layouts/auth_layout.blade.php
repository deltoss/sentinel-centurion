@extends('centurion::layouts/layout')

@section('title')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
    @parent

    @yield('centurion-title')
@endsection

@section('head')
	{{-- We use "@parent" to avoid overwriting the "head" section, and only appends to it. --}}
    @parent
    
    @yield('centurion-head')
@endsection

@section('body')
    @yield('centurion-content')
@endsection