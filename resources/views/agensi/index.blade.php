@extends('layouts.app')
@section('title', 'Data Agensi')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Data Agensi Wisata</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Kelola daftar agensi pariwisata yang bermitra dengan desa.
            </p>
        </div>
        <div>
            <a href="{{ route('agensi.create') }}" class="btn btn-success">+ Register Agensi Baru</a>
            <a href="{{ route('agensi.index', ['format' => 'json'] + request()->query()) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
        </div>
    </div>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('agensi.index') }}" style="margin-top: 1rem;">
        <div class="flex">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama agensi, nomor telepon, atau NPWP..." style="margin: 0; flex: 1;">
            <button type="submit" style="margin: 0;">Cari</button>
            @if($search)
                <a href="{{ route('agensi.index') }}" class="btn btn-secondary" style="margin: 0;">Reset</a>
            @endif
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nama Agensi</th>
                <th>Kontak / Telepon</th>
                <th>NPWP</th>
                <th>Akun Penanggung Jawab</th>
                <th>Jumlah Wisata</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agensi as $item)
                <tr>
                    <td>
                        <strong>{{ $item->nama_agensi }}</strong><br>
                        <small style="color: #6b7280;">ID: {{ $item->id }}</small>
                    </td>
                    <td>{{ $item->nomor_telepon }}</td>
                    <td>{{ $item->npwp ?: '-' }}</td>
                    <td>
                        @if($item->user)
                            <strong>{{ $item->user->username }}</strong><br>
                            <small style="color: #6b7280;">{{ $item->user->email }}</small>
                        @else
                            <span style="color: #9ca3af;">(Belum ada user)</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-gray">{{ $item->wisata?->count() ?? 0 }} Wisata</span>
                    </td>
                    <td>
                        <div class="flex">
                            <a href="{{ route('agensi.show', $item->id) }}" class="btn btn-sm btn-secondary">Detail</a>
                            <a href="{{ route('agensi.edit', $item->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('agensi.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus agensi ini?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7280; padding: 2rem;">
                        Tidak ada data agensi yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $agensi->links() }}
    </div>

    <div style="margin-top: 2rem;">
        <h3>Data JSON (Frontend Access)</h3>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

