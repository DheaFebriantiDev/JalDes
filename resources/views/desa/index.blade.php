@extends('layouts.app')
@section('title', 'Data Desa & Kepala Desa')
@section('content')
    <div class="flex-between">
        <h2>Data Desa &amp; Kepala Desa</h2>
        <div>
            <a href="{{ route('kepala-desa.create') }}" class="btn btn-success">+ Tambah Kepala Desa</a>
            <a href="{{ route('desa.create') }}" class="btn btn-secondary">+ Tambah Desa</a>
            <a href="{{ route('desa.index', ['format' => 'json'] + request()->query()) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
        </div>
    </div>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('desa.index') }}" style="margin-top: 1rem;">
        <div class="flex">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama desa, kecamatan, kota, provinsi, atau nama/NIPD kades..." style="margin: 0; flex: 1;">
            <button type="submit" style="margin: 0;">Cari</button>
            @if($search)
                <a href="{{ route('desa.index') }}" class="btn btn-secondary" style="margin: 0;">Reset</a>
            @endif
        </div>
    </form>

    @if($search)
        <p style="font-size: 0.9rem; color: #4b5563; margin-top: 0.5rem;">
            Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong> ({{ $desaList->count() }} desa ditemukan)
        </p>
    @endif

    <!-- Tabel Data Desa (Urutan: Desa A-Z, lalu Kades A-Z) -->
    <table>
        <thead>
            <tr>
                <th>Kode Pos</th>
                <th>Nama Desa</th>
                <th>Kecamatan</th>
                <th>Kota / Kabupaten</th>
                <th>Provinsi</th>
                <th>Kepala Desa</th>
                <th>Status Kades</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($desaList as $desa)
                <tr>
                    <td><strong>{{ $desa->kode_pos }}</strong></td>
                    <td>{{ $desa->nama_desa }}</td>
                    <td>{{ $desa->kecamatan }}</td>
                    <td>{{ $desa->kota }}</td>
                    <td>{{ $desa->provinsi }}</td>
                    <td>
                        @if($desa->kepalaDesa)
                            <strong>{{ $desa->kepalaDesa->nama }}</strong><br>
                            <small style="color: #6b7280;">NIPD: {{ $desa->kepalaDesa->nipd }}</small>
                        @else
                            <span style="color: #9ca3af;">(Belum ada kades)</span>
                        @endif
                    </td>
                    <td>
                        @if($desa->kepalaDesa)
                            @if($desa->kepalaDesa->status)
                                <span class="badge badge-success">Menjabat</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        @else
                            <span class="badge badge-gray">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex">
                            <a href="{{ route('desa.show', $desa->kode_pos) }}" class="btn btn-sm btn-secondary">Detail</a>
                            <a href="{{ route('desa.edit', $desa->kode_pos) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('desa.destroy', $desa->kode_pos) }}" onsubmit="return confirm('Yakin ingin menghapus desa ini?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #6b7280; padding: 2rem;">
                        Tidak ada data desa yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- JSON Preview untuk frontend developer -->
    <div style="margin-top: 2rem;">
        <h3>Data JSON (Mempermudah Frontend Integration)</h3>
        <p style="font-size: 0.85rem; color: #6b7280;">
            Data di bawah ini mencakup seluruh informasi desa dan kecamatannya beserta relasi kepala desa terkait.
        </p>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

