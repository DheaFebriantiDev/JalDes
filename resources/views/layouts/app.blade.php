<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'JalDes')</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f3f4f6; margin: 0; padding: 2rem; color: #111827; }
        .card { max-width: 960px; margin: 0 auto; background: #fff; padding: 1.5rem 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.75rem; }
        .nav a { color: #2563eb; text-decoration: none; font-weight: 500; margin-right: 1rem; }
        .nav a:hover { text-decoration: underline; }
        label { display: block; margin-top: 1rem; font-weight: 600; font-size: 0.9rem; }
        input[type=text], input[type=password], input[type=number], input[type=email], select {
            width: 100%; padding: .5rem; margin-top: .25rem; box-sizing: border-box; border: 1px solid #d1d5db; border-radius: 4px;
        }
        button, .btn {
            display: inline-block; margin-top: 1rem; padding: .5rem 1rem; cursor: pointer; border-radius: 4px; border: none;
            background: #2563eb; color: #fff; font-size: 0.9rem; text-decoration: none; font-weight: 500;
        }
        .btn-secondary { background: #6b7280; }
        .btn-danger { background: #dc2626; }
        .btn-success { background: #16a34a; }
        .btn-sm { padding: .25rem .5rem; font-size: 0.8rem; margin: 0 .25rem; }
        .error { color: #b91c1c; font-size: 0.85rem; margin-top: .25rem; }
        .alert { padding: .75rem 1rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: 0.9rem; }
        th, td { border: 1px solid #e5e7eb; padding: .65rem .75rem; text-align: left; }
        th { background: #f9fafb; font-weight: 600; }
        tr:nth-child(even) { background: #fafafa; }
        .badge { display: inline-block; padding: .2rem .5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-gray { background: #f3f4f6; color: #4b5563; }
        pre { background: #111827; color: #d1fae5; padding: 1rem; border-radius: 6px; overflow: auto; max-height: 400px; font-size: 0.85rem; }
        .flex { display: flex; gap: 0.5rem; align-items: center; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="nav">
            <div>
                <strong>JalDes Backend</strong>
                @auth
                    <span style="margin-left: 0.5rem; font-size: 0.8rem; color: #6b7280;">({{ auth()->user()->role }})</span>
                @endauth
            </div>
            <div>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @auth
                    @if(auth()->user()->isAgensi())
                        <a href="{{ route('wisata.index') }}">Wisata Saya</a>
                        <a href="{{ route('draft-prompt.index') }}">Draft Promosi</a>
                    @elseif(auth()->user()->isKepalaDesa())
                        <a href="{{ route('wisata.index') }}">Verifikasi Wisata</a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('desa.index') }}">Kelola Desa</a>
                        <a href="{{ route('kepala-desa.index') }}">Data Kades</a>
                        <a href="{{ route('wisata.index') }}">Wisata</a>
                        <a href="{{ route('draft-prompt.index') }}">Draft Promosi</a>
                    @endif
                @endauth
                @auth
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary" style="margin: 0;">Logout</button>
                    </form>
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
