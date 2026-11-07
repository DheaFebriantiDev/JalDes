@extends('layouts.app')
@section('title', 'Dashboard Agensi')
@section('content')
    <h1>Halo, {{ $user->username }}</h1>
    <p>Nama agensi: <strong>{{ $agensi?->nama_agensi ?? '-' }}</strong></p>

    <h3>Data user &amp; agensi (JSON)</h3>
    <pre>{{ json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    @include('dashboard._logout')
@endsection
