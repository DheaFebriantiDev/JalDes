@extends('layouts.app')
@section('title', 'Daftar Objek Wisata')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Objek Wisata</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                @if(auth()->user()?->isAgensi())
                    Kelola objek wisata yang didaftarkan oleh agensi Anda.
                @elseif(auth()->user()?->isKepalaDesa())
                    Daftar objek wisata di wilayah desa Anda yang memerlukan verifikasi.
                @else
                    Daftar seluruh objek wisata desa.
                @endif
            </p>
        </div>
        <div>
            @if(auth()->user()?->isAgensi() || auth()->user()?->isAdmin())
                <a href="{{ route('wisata.create') }}" class="btn btn-success">+ Ajukan Wisata Baru</a>
            @endif
            <a href="{{ route('wisata.index', ['format' => 'json'] + request()->query()) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <form method="GET" action="{{ route('wisata.index') }}" style="margin-top: 1rem;">
        <div class="flex">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama wisata, jalan, desa, kecamatan, agensi..." style="margin: 0; flex: 1;">
            
            <select name="status" style="width: auto; margin: 0;">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <button type="submit" style="margin: 0;">Filter</button>
            @if($search || $statusFilter)
                <a href="{{ route('wisata.index') }}" class="btn btn-secondary" style="margin: 0;">Reset</a>
            @endif
        </div>
    </form>

    <!-- Tabel Wisata -->
    <table>
        <thead>
            <tr>
                <th>Nama Wisata</th>
                <th>Wilayah / Desa</th>
                <th>Agensi</th>
                <th>Video Promosi</th>
                <th>Status Verifikasi</th>
                <th>Kades Verifikator</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wisataList as $w)
                <tr>
                    <td>
                        <strong>{{ $w->nama_wisata }}</strong><br>
                        <small style="color: #6b7280;">{{ $w->jalan }}</small>
                    </td>
                    <td>
                        @if($w->desa)
                            {{ $w->desa->nama_desa }}<br>
                            <small style="color: #6b7280;">Kec. {{ $w->desa->kecamatan }} ({{ $w->kode_pos }})</small>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                    <td>{{ $w->agensi?->nama_agensi ?? '-' }}</td>
                    <td>
                        @if($w->video_promosi)
                            @php
                                $isExternalUrl = \Illuminate\Support\Str::startsWith($w->video_promosi, ['http://', 'https://']);
                                $videoSrc = $isExternalUrl ? $w->video_promosi : asset('storage/' . $w->video_promosi);
                            @endphp
                            <video controls class="w-full max-w-lg rounded-lg shadow-md" style="width: 200px; border-radius: 6px; display: block;">
                                <source src="{{ $videoSrc }}" type="video/mp4">
                                Browser Anda tidak mendukung tag video.
                            </video>
                        @else
                            <p class="text-gray-500" style="font-size: 0.75rem; color: #6b7280; margin: 0;">Video promosi sedang diproses atau belum tersedia.</p>
                        @endif
                    </td>
                    <td>
                        @if(is_null($w->status))
                            <span class="badge badge-gray">Menunggu Verifikasi</span>
                        @elseif($w->status)
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        {{ $w->kepalaDesa?->nama ?? '-' }}<br>
                        <small style="color: #6b7280;">NIPD: {{ $w->nipd }}</small>
                    </td>
                    <td>
                        <div class="flex" style="flex-wrap: wrap;">
                            <a href="{{ route('wisata.show', $w->id) }}" class="btn btn-sm btn-secondary">Detail</a>

                            @if(auth()->user()?->isAgensi() && auth()->user()->agensi?->id === $w->agensi_id || auth()->user()?->isAdmin())
                                <a href="{{ route('wisata.edit', $w->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form method="POST" action="{{ route('wisata.destroy', $w->id) }}" onsubmit="return confirm('Hapus wisata ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endif

                            <!-- Aksi Khusus Kepala Desa Verifikator -->
                            @if(auth()->user()?->isKepalaDesa() && auth()->user()->kepalaDesa?->nipd === $w->nipd)
                                @if(is_null($w->status) || ! $w->status)
                                    <form method="POST" action="{{ route('wisata.verifikasi', $w->id) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="approve">
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                @endif
                                @if(is_null($w->status) || $w->status)
                                    <form method="POST" action="{{ route('wisata.verifikasi', $w->id) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="reject">
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #6b7280; padding: 2rem;">
                        Tidak ada objek wisata yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- JSON Preview -->
    <div style="margin-top: 2rem;">
        <h3>Data JSON (Katalog Wisata &amp; Verifikasi)</h3>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

