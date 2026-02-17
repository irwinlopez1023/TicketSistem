<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite([
     'resources/css/app.css',
     'resources/css/bootstrap.css',
 ])
    @stack('styles')
</head>
<body>
<x-bd-theme></x-bd-theme>
<x-icons></x-icons>

@include('layouts.partials.header')
<div class="container-fluid">
    <div class="row">
        @include('layouts.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="row mt-3">
                <div class="col-12">
                    {{ $slot }}
                </div>
            </div>

        </main>
    </div>
</div>

@vite(['resources/js/bootstrap.js','resources/js/color-modes.js'])
@stack('scripts')
</body>
</html>
