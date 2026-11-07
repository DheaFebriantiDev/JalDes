@extends('layouts.app')
@section('title', 'Data Kepala Desa')
@section('content')
    <div class="flex-between">
        <h2>Data Kepala Desa</h2>
        <div>
            <a href="{{ route('kepala-desa.create') }}" class="btn btn-success">+ Tambah Kepala Desa</a>
            <a href="{{ route('desa.index') }}" class="btn btn-secondary">Kelola Desa</a>
            <a href="{{ route('kepala-desa.index', ['format' => 'json']) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
        </div>
    </div>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('kepala-desa.index') }}" style="margin-top: 1rem;">
        <div class="flex">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kades, NIPD, NIK, atau nama desa..." style="margin: 0; flex: 1;">
            <button type="submit" style="margin: 0;">Cari</button>
            @if($search)
                <a href="{{ route('kepala-desa.index') }}" class="btn btn-secondary" style="margin: 0;">Reset</a>
            @endif
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>NIPD</th>
                <th>Nama Kepala Desa</th>
                <th>NIK</th>
                <th>Nomor Telepon</th>
                <th>Desa yang Dipimpin</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kadesList as $kades)
                <tr>
                    <td><code>{{ $kades->nipd }}</code></td>
                    <td><strong>{{ $kades->nama }}</strong></td>
                    <td>{{ $kades->nik }}</td>
                    <td>{{ $kades->nomor_telepon }}</td>
                    <td>
                        @if($kades->desa)
                            {{ $kades->desa->nama_desa }} (Kec. {{ $kades->desa->kecamatan }}, {{ $kades->desa->kode_pos }})
                        @else
                            <span style="color: #9ca3af;">(Tidak terhubung desa)</span>
                        @endif
                    </td>
                    <td>
                        @if($kades->status)
                            <span class="badge badge-success">Menjabat (Aktif)</span>
                        @else
                            <span class="badge badge-danger">Non-aktif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7280; padding: 2rem;">
                        Tidak ada data kepala desa yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 2rem;">
        <h3>Data JSON (Frontend Access)</h3>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

