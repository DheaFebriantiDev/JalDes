@extends('layouts.app')
@section('title', 'Detail Wisata - ' . $wisata->nama_wisata)
@section('content')
    <div class="flex-between">
        <div>
            <h2>{{ $wisata->nama_wisata }}</h2>
            <p style="color: #6b7280; margin-top: 0.25rem;">
                Didaftarkan oleh Agensi: <strong>{{ $wisata->agensi?->nama_agensi ?? '-' }}</strong>
            </p>
        </div>
        <div>
            @if(auth()->user()?->isAgensi() && auth()->user()->agensi?->id === $wisata->agensi_id || auth()->user()?->isAdmin())
                <a href="{{ route('wisata.edit', $wisata->id) }}" class="btn btn-secondary">Edit</a>
            @endif
            <a href="{{ route('wisata.show', [$wisata->id, 'format' => 'json']) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
            <a href="{{ route('wisata.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- Kotak Status Verifikasi -->
    <div style="margin-top: 1.5rem; padding: 1.25rem; border-radius: 6px; background: #f9fafb; border: 1px solid #e5e7eb;">
        <div class="flex-between">
            <div>
                <strong>Status Verifikasi:</strong>
                @if(is_null($wisata->status))
                    <span class="badge badge-gray" style="font-size: 0.9rem;">Menunggu Verifikasi Kepala Desa</span>
                @elseif($wisata->status)
                    <span class="badge badge-success" style="font-size: 0.9rem;">Disetujui (Aktif Dipromosikan)</span>
                @else
                    <span class="badge badge-danger" style="font-size: 0.9rem;">Ditolak / Perlu Perbaikan</span>
                @endif
            </div>

            <!-- Panel Verifikasi Kades -->
            @if(auth()->user()?->isKepalaDesa() && auth()->user()->kepalaDesa?->nipd === $wisata->nipd)
                <div class="flex">
                    <form method="POST" action="{{ route('wisata.verifikasi', $wisata->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="approve">
                        <button type="submit" class="btn btn-sm btn-success" {{ $wisata->status === true ? 'disabled' : '' }}>✓ Setujui Wisata</button>
                    </form>

                    <form method="POST" action="{{ route('wisata.verifikasi', $wisata->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="reject">
                        <button type="submit" class="btn btn-sm btn-danger" {{ $wisata->status === false ? 'disabled' : '' }}>✕ Tolak</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Informasi Lokasi & Verifikator -->
    <div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0;">Lokasi Wilayah</h3>
            <p><strong>Alamat / Jalan:</strong> {{ $wisata->jalan }}</p>
            <p><strong>Desa:</strong> {{ $wisata->desa?->nama_desa ?? '-' }}</p>
            <p><strong>Kecamatan:</strong> {{ $wisata->desa?->kecamatan ?? '-' }}</p>
            <p><strong>Kota / Kabupaten:</strong> {{ $wisata->desa?->kota ?? '-' }}</p>
            <p><strong>Kode Pos:</strong> {{ $wisata->kode_pos ?? '-' }}</p>
        </div>

        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0;">Media &amp; Verifikator</h3>
            <p><strong>Kepala Desa Verifikator:</strong> {{ $wisata->kepalaDesa?->nama ?? '-' }} (NIPD: {{ $wisata->nipd }})</p>
            <p><strong>Gambar Utama:</strong> <code>{{ $wisata->gambar }}</code></p>
            <p><strong>Video Promosi:</strong> <code>{{ $wisata->video_promosi }}</code></p>
            <p><strong>Video Full:</strong> <code>{{ $wisata->video }}</code></p>
        </div>
    </div>

    <div style="margin-top: 1rem; background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
        <h3 style="margin-top: 0;">Deskripsi</h3>
        <p style="color: #374151; line-height: 1.6; margin: 0;">
            {{ $wisata->deskripsi ?: '(Tidak ada deskripsi)' }}
        </p>
    </div>

    @if($wisata->draftPrompt)
        <div style="margin-top: 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1.25rem; border-radius: 6px;">
            <h3 style="margin-top: 0; color: #166534;">Draft Prompt Promosi AI Terkait</h3>
            <p style="margin: 0; color: #15803d;">
                <em>"{{ $wisata->draftPrompt->prompt_message }}"</em>
            </p>
        </div>
    @endif

    <div style="margin-top: 2rem;">
        <h3>Data JSON Lengkap (Frontend Access)</h3>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

