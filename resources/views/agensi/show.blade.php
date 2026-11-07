@extends('layouts.app')
@section('title', 'Detail Agensi - ' . $agensi->nama_agensi)
@section('content')
    <div class="flex-between">
        <div>
            <h2>Detail Agensi: {{ $agensi->nama_agensi }}</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                UUID: <code>{{ $agensi->id }}</code>
            </p>
        </div>
        <div>
            <a href="{{ route('agensi.edit', $agensi->id) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('agensi.show', [$agensi->id, 'format' => 'json']) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
            <a href="{{ route('agensi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0;">Informasi Kontak &amp; Legalitas</h3>
            <p><strong>Nama Agensi:</strong> {{ $agensi->nama_agensi }}</p>
            <p><strong>Nomor Telepon:</strong> {{ $agensi->nomor_telepon }}</p>
            <p><strong>NPWP:</strong> {{ $agensi->npwp ?: '(Belum diisi)' }}</p>
            <p><strong>Deskripsi:</strong> {{ $agensi->deskripsi ?: '(Tidak ada deskripsi)' }}</p>
        </div>

        <div style="background: #f9fafb; padding: 1.25rem; border-radius: 6px; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0;">Akun Penanggung Jawab Utama</h3>
            @if($agensi->user)
                <p><strong>Username:</strong> {{ $agensi->user->username }}</p>
                <p><strong>Email:</strong> {{ $agensi->user->email }}</p>
                <p><strong>Role:</strong> <span class="badge badge-success">{{ $agensi->user->role }}</span></p>
                <p><strong>Terdaftar Sejak:</strong> {{ $agensi->user->created_at?->translatedFormat('d F Y') }}</p>
            @else
                <p style="color: #6b7280;">Belum terhubung dengan akun user.</p>
            @endif
        </div>
    </div>

    <!-- Objek Wisata Milik Agensi -->
    <div style="margin-top: 1.5rem;">
        <h3>Daftar Objek Wisata yang Didaftarkan ({{ $agensi->wisata?->count() ?? 0 }})</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Wisata</th>
                    <th>Jalan</th>
                    <th>Kode Pos</th>
                    <th>Status Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agensi->wisata as $w)
                    <tr>
                        <td><strong>{{ $w->nama_wisata }}</strong></td>
                        <td>{{ $w->jalan }}</td>
                        <td>{{ $w->kode_pos }}</td>
                        <td>
                            @if(is_null($w->status))
                                <span class="badge badge-gray">Menunggu Verifikasi</span>
                            @elseif($w->status)
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6b7280;">Belum ada objek wisata yang didaftarkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        <h3>Data JSON Lengkap (Frontend Access)</h3>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

