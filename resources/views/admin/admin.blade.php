<!DOCTYPE html>
<html lang="fr">
<head>
    <title>@yield('title') | Administration</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('static/css/output.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/tailwind.min.css">
</head>
<body class="bg-gray-100">

    @includeWhen(Auth::check() && Auth::user()->role == 'admin', 'includes.head_connected_admin')
    @includeWhen(Auth::check() && Auth::user()->role != 'admin', 'includes.head_connected_user')
    @includeWhen(!Auth::check(), 'includes.head')

</body>
</html>