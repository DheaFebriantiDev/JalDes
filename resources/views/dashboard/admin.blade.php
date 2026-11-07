@extends('layouts.app')
@section('title', 'Dashboard Administrasi Desa (Admin)')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Dashboard Administrasi</h2>
            <p style="color: #6b7280; margin-top: 0.25rem;">
                Selamat datang, <strong>{{ $user->username }}</strong> (Administrator Sistem).
            </p>
        </div>
        <div>
            <a href="{{ route('kepala-desa.create') }}" class="btn btn-success">+ Tambah Kepala Desa</a>
            <a href="{{ route('desa.index') }}" class="btn btn-secondary">Kelola Desa</a>
        </div>
    </div>

    <!-- Ringkasan Statistik Administrasi -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 1.5rem 0;">
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.85rem; color: #166534; font-weight: 600;">TOTAL DESA</div>
            <div style="font-size: 2rem; font-weight: bold; color: #15803d; margin-top: 0.25rem;">{{ $totalDesa }}</div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.85rem; color: #1e40af; font-weight: 600;">TOTAL KEPALA DESA</div>
            <div style="font-size: 2rem; font-weight: bold; color: #1d4ed8; margin-top: 0.25rem;">{{ $totalKades }}</div>
        </div>

        <div style="background: #fefce8; border: 1px solid #fef08a; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.85rem; color: #854d0e; font-weight: 600;">KADES MENJABAT (AKTIF)</div>
            <div style="font-size: 2rem; font-weight: bold; color: #a16207; margin-top: 0.25rem;">{{ $kadesAktif }}</div>
        </div>
    </div>

    <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
        <h3 style="margin-top: 0;">Hak Akses &amp; Batasan Role Admin</h3>
        <p style="font-size: 0.9rem; color: #4b5563;">
            Role <strong>Admin</strong> memiliki permission khusus untuk:
        </p>
        <ul style="font-size: 0.9rem; color: #4b5563; padding-left: 1.25rem;">
            <li>Mengelola data wilayah desa (tambah, lihat, perbarui, dan hapus desa).</li>
            <li>Mengatur penugasan kepala desa dan pergantian kades menjabat dengan algoritma otomatis.</li>
            <li>Role ini terpisah dan tidak dapat mengakses modul khusus agensi maupun fitur operasional kepala desa.</li>
        </ul>
        <div style="margin-top: 1rem;">
            <a href="{{ route('desa.index') }}" class="btn">Buka Halaman CRUD Desa &amp; Kepala Desa &rarr;</a>
        </div>
    </div>

    <div style="margin-top: 1.5rem;">
        @include('dashboard._logout')
    </div>
@endsection

