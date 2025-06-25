<!-- resources/views/app.blade.php -->
<!-- Este arquivo já foi revisado nos módulos 2 e 3 e permanece consistente. -->
<!-- Ele define o template principal onde sua aplicação Vue/Inertia será montada. -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- ESSA LINHA É CRÍTICA PARA O ERRO 419 -->

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fontes padrão do Laravel/Breeze, Figtree -->
    <link rel="preconnect" href="[https://fonts.bunny.net](https://fonts.bunny.net)">
    <link href="[https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap](https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap)" rel="stylesheet" />

    <!-- Scripts da aplicação Vite, incluindo app.js e a página Vue atual -->
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
