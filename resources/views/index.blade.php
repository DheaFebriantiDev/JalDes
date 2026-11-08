<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JalDes - Jaringan Desa</title>
    
    <!-- Memanggil CSS hasil build Vue -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) --> <!-- ATAU kalau pakai struktur build manual kita sebelumnya: -->
    <link rel="stylesheet" href="{{ asset('build/assets/index.css') }}">
</head>
<body>
    <!-- Tempat di mana seluruh aplikasi Vue kamu akan dirender -->
    <div id="app"></div>

    <!-- Memanggil JS hasil build Vue -->
    <script type="module" src="{{ asset('build/assets/index.js') }}"></script>
</body>
</html>