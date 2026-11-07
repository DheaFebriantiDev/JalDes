@extends('layouts.app')
@section('title', 'Detail Desa - ' . $desa->nama_desa)
@section('content')
    <div class="flex-between">
        <h2>Detail Desa: {{ $desa->nama_desa }}</h2>
        <div>
            <a href="{{ route('desa.edit', $desa->kode_pos) }}" class="btn btn-secondary">Edit Desa</a>
            <a href="{{ route('desa.show', [$desa->kode_pos, 'format' => 'json']) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
            <a href="{{ route('desa.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0;">Wilayah Desa</h3>
            <p><strong>Kode Pos:</strong> {{ $desa->kode_pos }}</p>
            <p><strong>Nama Desa:</strong> {{ $desa->nama_desa }}</p>
            <p><strong>Kecamatan:</strong> {{ $desa->kecamatan }}</p>
            <p><strong>Kota / Kabupaten:</strong> {{ $desa->kota }}</p>
            <p><strong>Provinsi:</strong> {{ $desa->provinsi }}</p>
        </div>

        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0;">Kepala Desa yang Menjabat</h3>
            @if($desa->kepalaDesa)
                <p><strong>Nama:</strong> {{ $desa->kepalaDesa->nama }}</p>
                <p><strong>NIPD:</strong> {{ $desa->kepalaDesa->nipd }}</p>
                <p><strong>NIK:</strong> {{ $desa->kepalaDesa->nik }}</p>
                <p><strong>Nomor Telepon:</strong> {{ $desa->kepalaDesa->nomor_telepon }}</p>
                <p>
                    <strong>Status:</strong>
                    @if($desa->kepalaDesa->status)
                        <span class="badge badge-success">Aktif (Sedang Menjabat)</span>
                    @else
                        <span class="badge badge-danger">Nonaktif</span>
                    @endif
                </p>
            @else
                <p style="color: #6b7280;">Belum ada kepala desa yang ditugaskan di desa ini.</p>
                <a href="{{ route('desa.edit', $desa->kode_pos) }}" class="btn btn-sm btn-secondary">Tugaskan Kepala Desa</a>
            @endif
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <h3>Data JSON Lengkap (Frontend Access)</h3>
        <p style="font-size: 0.85rem; color: #6b7280;">
            Struktur data JSON desa beserta kecamatan dan data kepala desa terkait:
        </p>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

