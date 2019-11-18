<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <style>
    @font-face {
      font-family: 'arialuni';
      src: url("{{ storage_path('fonts/arialuni.ttf') }}");
      font-weight: 400;
      font-style: normal;
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/index.css') }}">
  <title>S7</title>
</head>
<body>
  <noscript>Enable javascript to view this website</noscript>
  <div id="root"></div>
  <script src="js/app.js"></script>
</body>
</html>
