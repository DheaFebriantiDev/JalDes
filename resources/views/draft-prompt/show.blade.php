@extends('layouts.app')
@section('title', 'Detail Draft Prompt')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Detail Draft Prompt</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Wisata: <strong>{{ $draft->wisata?->nama_wisata ?? '-' }}</strong>
            </p>
        </div>
        <div>
            <a href="{{ route('draft-prompt.show', [$draft->id, 'format' => 'json']) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
            <a href="{{ route('draft-prompt.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb; margin-top: 1.5rem;">
        <h3 style="margin-top: 0;">Isi Prompt Promosi</h3>
        <p style="font-size: 1.1rem; line-height: 1.6; color: #1f2937; margin: 0; font-style: italic;">
            "{{ $draft->prompt_message }}"
        </p>
    </div>

    <div style="margin-top: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h4 style="margin-top: 0;">Informasi Objek Wisata</h4>
            <p><strong>Nama:</strong> {{ $draft->wisata?->nama_wisata ?? '-' }}</p>
            <p><strong>Alamat:</strong> {{ $draft->wisata?->jalan ?? '-' }}</p>
            <p><strong>Desa:</strong> {{ $draft->wisata?->desa?->nama_desa ?? '-' }}</p>
            @if($draft->wisata)
                <a href="{{ route('wisata.show', $draft->wisata->id) }}" class="btn btn-sm btn-secondary" style="margin-top: 0.5rem;">Lihat Detail Wisata &rarr;</a>
            @endif
        </div>

        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h4 style="margin-top: 0;">Metadata</h4>
            <p><strong>Agensi Pembuat:</strong> {{ $draft->agensi?->nama_agensi ?? '-' }}</p>
            <p><strong>Waktu Dibuat:</strong> {{ $draft->timestamp?->translatedFormat('d F Y, H:i:s') ?? '-' }}</p>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <h3>Data JSON (Frontend &amp; AI Integration)</h3>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

