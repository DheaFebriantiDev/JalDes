@extends('layouts.app')
@section('title', 'Dashboard Kepala Desa')
@section('content')
    <div class="flex-between">
        <div>
            <h1>Halo pak kades</h1>
            <p style="color: #6b7280; margin-top: 0.25rem;">
                Selamat datang, <strong>{{ $kepalaDesa?->nama ?? $user->username }}</strong>.
                @if($desa)
                    Anda memimpin <strong>{{ $desa->nama_desa }}</strong> (Kec. {{ $desa->kecamatan }}, Pos: {{ $desa->kode_pos }}).
                @endif
            </p>
        </div>
        <div>
            <span class="badge badge-success" style="font-size: 0.9rem;">Status: Aktif Menjabat</span>
        </div>
    </div>

    <!-- Panel Informasi Wisata yang Perlu Diverifikasi -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin: 1.5rem 0;">
        <div style="background: #fffbeb; border: 1px solid #fde68a; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.85rem; color: #b45309; font-weight: 600;">WISATA MENUNGGU VERIFIKASI</div>
            <div style="font-size: 2rem; font-weight: bold; color: #d97706; margin-top: 0.25rem;">{{ $wisataPending }}</div>
            <small style="color: #92400e;">Objek wisata yang diajukan agensi</small>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.85rem; color: #166534; font-weight: 600;">WISATA DISETUJUI</div>
            <div style="font-size: 2rem; font-weight: bold; color: #15803d; margin-top: 0.25rem;">{{ $wisataApproved }}</div>
            <small style="color: #166534;">Aktif dipromosikan ke publik</small>
        </div>
    </div>

    <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px; margin-bottom: 1.5rem;">
        <h3 style="margin-top: 0;">Tugas &amp; Tanggung Jawab Kepala Desa:</h3>
        <p style="font-size: 0.9rem; color: #4b5563; margin-bottom: 1rem;">
            Sebagai Kepala Desa yang sedang menjabat di wilayah ini, Anda memiliki otoritas untuk memverifikasi dan menyetujui setiap objek wisata yang didaftarkan oleh agensi pariwisata.
        </p>
        <a href="{{ route('wisata.index') }}" class="btn btn-success">Buka Halaman Verifikasi Wisata &rarr;</a>
    </div>

    @include('dashboard._logout')
@endsection
