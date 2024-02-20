<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <meta name="HandheldFriendly" content="true">
    <meta name="MobileOptimized" content="width">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="saythe">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('settings.title') }}">
    <meta property="og:site_name" content="{{ config('settings.title') }}">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1155">
    <meta property="og:image:height" content="400">
    <meta property="og:description" content="{{ config('settings.description') }}">
    <meta property="og:image" content="./assets/images/pictures.png">

    <link rel="apple-touch-icon" href="./assets/images/logo.png">
    <link rel="shortcut icon" href="./assets/images/logo.ico">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('settings.title') }}</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>

    @csrf
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div id="app"></div>

</body>

</html>