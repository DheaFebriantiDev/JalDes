@extends('layouts.app')
@section('title', 'Login - JalDes')
@section('content')
    <h1>Login</h1>
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <label for="login">Username atau Email</label>
        <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus>
        @error('login') <div class="error">{{ $message }}</div> @enderror

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <label><input type="checkbox" name="remember" value="1"> Ingat saya</label>

        <button type="submit">Masuk</button>

        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; font-size: 0.9rem;">
            Belum memiliki akun agensi pariwisata?
            <a href="{{ route('agensi.create') }}" style="color: #2563eb; font-weight: 600;">Daftar sebagai Agensi Baru &rarr;</a>
        </div>
    </form>
@endsection
