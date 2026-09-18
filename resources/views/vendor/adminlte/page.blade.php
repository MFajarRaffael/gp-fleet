@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
@stack('css')
@yield('css')

{{-- CSS Global Search --}}
<link rel="stylesheet" href="{{ asset('css/navbar-search.css') }}">
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
<div class="wrapper">

    {{-- Preloader --}}
    @if(config('adminlte.preloader.enabled'))
        @include('adminlte::partials.common.preloader')
    @endif

    @include('adminlte::partials.navbar.navbar')

    @include('adminlte::partials.sidebar.left-sidebar')

    <div class="content-wrapper">
        @yield('content_header')

        <div class="content">
            @yield('content')
        </div>
    </div>

    @include('adminlte::partials.footer.footer')

</div>
@stop

@section('adminlte_js')
@stack('js')
@yield('js')

{{-- JS Global Search --}}
<script src="{{ asset('js/global-search.js') }}"></script>
@stop