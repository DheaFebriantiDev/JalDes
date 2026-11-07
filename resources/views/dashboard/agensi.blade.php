@extends('layouts.app')
@section('title', 'Dashboard Agensi')
@section('content')
    <div class="flex-between" style="border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1.5rem;">
        <div>
            <h1 style="margin: 0; font-size: 1.75rem;">Halo, {{ $user->username }} 👋</h1>
            <p style="margin: 0.25rem 0 0 0; color: #4b5563;">
                Agensi: <strong>{{ $agensi?->nama_agensi ?? '-' }}</strong> | Telepon: {{ $agensi?->nomor_telepon ?? '-' }}
            </p>
        </div>
        <div>
            <a href="{{ route('wisata.create') }}" class="btn btn-success" style="font-size: 1rem; padding: 0.65rem 1.25rem; font-weight: 600; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);">
                + Tambah Wisata
            </a>
        </div>
    </div>

    {{-- Kartu Ringkasan Statistik --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; text-align: center;">
            <div style="font-size: 0.85rem; color: #6b7280; text-transform: uppercase; font-weight: 600;">Total Wisata</div>
            <div style="font-size: 2rem; font-weight: 700; color: #111827; margin-top: 0.25rem;">{{ $totalWisata }}</div>
        </div>
        <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 8px; padding: 1rem; text-align: center;">
            <div style="font-size: 0.85rem; color: #854d0e; text-transform: uppercase; font-weight: 600;">Menunggu Verifikasi</div>
            <div style="font-size: 2rem; font-weight: 700; color: #a16207; margin-top: 0.25rem;">{{ $wisataPending }}</div>
        </div>
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1rem; text-align: center;">
            <div style="font-size: 0.85rem; color: #166534; text-transform: uppercase; font-weight: 600;">Disetujui Kades</div>
            <div style="font-size: 2rem; font-weight: 700; color: #15803d; margin-top: 0.25rem;">{{ $wisataApproved }}</div>
        </div>
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 1rem; text-align: center;">
            <div style="font-size: 0.85rem; color: #991b1b; text-transform: uppercase; font-weight: 600;">Ditolak Kades</div>
            <div style="font-size: 2rem; font-weight: 700; color: #dc2626; margin-top: 0.25rem;">{{ $wisataRejected }}</div>
        </div>
    </div>

    {{-- Daftar Wisata Agensi --}}
    <div class="flex-between" style="margin-bottom: 0.75rem;">
        <h3 style="margin: 0;">Objek Wisata Terdaftar</h3>
        <div>
            <a href="{{ route('wisata.index') }}" class="btn btn-secondary btn-sm">Lihat Semua di Halaman Wisata &rarr;</a>
        </div>
    </div>

    @if($wisataList->isEmpty())
        <div style="background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 8px; padding: 2rem; text-align: center; margin-bottom: 2rem;">
            <p style="color: #6b7280; margin-bottom: 1rem;">Anda belum mendaftarkan objek wisata apapun.</p>
            <a href="{{ route('wisata.create') }}" class="btn btn-success">+ Tambah Objek Wisata Sekarang</a>
        </div>
    @else
        <table style="margin-bottom: 2rem;">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Nama Wisata</th>
                    <th>Wilayah (Desa &amp; Kades)</th>
                    <th>Video Promosi &amp; AI Prompt</th>
                    <th>Status Kades</th>
                    <th style="text-align: center; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wisataList as $w)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $w->nama_wisata }}</strong>
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ $w->jalan }}</div>
                        </td>
                        <td>
                            <div>{{ $w->desa?->nama_desa ?? '-' }} (Pos: {{ $w->kode_pos ?? '-' }})</div>
                            <div style="font-size: 0.8rem; color: #4b5563;">Kec. {{ $w->desa?->kecamatan ?? '-' }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">Kades: {{ $w->kepalaDesa?->nama ?? 'Belum ada' }}</div>
                        </td>
                        <td>
                            @if($w->draftPrompt)
                                <span class="badge" style="background: #e0e7ff; color: #3730a3; margin-bottom: 0.25rem;">AI Generated (Fal.ai)</span>
                                <div style="font-size: 0.75rem; color: #4b5563; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.35rem;" title="{{ $w->draftPrompt->prompt_message }}">
                                    "{{ $w->draftPrompt->prompt_message }}"
                                </div>
                            @else
                                <span class="badge badge-gray" style="margin-bottom: 0.25rem;">Manual Video</span>
                            @endif

                            @if(is_null($w->video_promosi) && $w->draftPrompt)
                                <span class="text-sm text-yellow-600 italic ai-pending-indicator" style="display: inline-flex; align-items: center; color: #d97706; font-style: italic; font-size: 0.85rem; margin-top: 0.25rem;">
                                    <svg class="animate-spin h-4 w-4 inline mr-1" style="width: 1rem; height: 1rem; margin-right: 0.25rem; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    ⏳ Memproses Video AI (Estimasi 1-2 Menit)...
                                </span>
                            @elseif($w->video_promosi)
                                @php
                                    $isExternalUrl = \Illuminate\Support\Str::startsWith($w->video_promosi, ['http://', 'https://']);
                                    $videoSrc = $isExternalUrl ? $w->video_promosi : asset('storage/' . $w->video_promosi);
                                @endphp
                                <video controls class="w-full max-w-lg rounded-lg shadow-md" style="width: 220px; border-radius: 6px; display: block;">
                                    <source src="{{ $videoSrc }}" type="video/mp4">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            @else
                                <p class="text-gray-500" style="font-size: 0.75rem; color: #6b7280; margin: 0;">Video promosi sedang diproses atau belum tersedia.</p>
                            @endif
                        </td>
                        <td>
                            @if(is_null($w->status))
                                <span class="badge" style="background: #fef08a; color: #854d0e;">Pending</span>
                            @elseif($w->status === true)
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('wisata.show', $w->id) }}" class="btn btn-secondary btn-sm" style="margin: 0 2px;">Detail</a>
                            <a href="{{ route('wisata.edit', $w->id) }}" class="btn btn-sm" style="background: #2563eb; color: #fff; margin: 0 2px;">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Akses Cepat Draft Prompt --}}
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 2rem;">
        <div class="flex-between">
            <div>
                <strong style="color: #1e293b;">Pusat Promosi AI (Fal.ai Prompts)</strong>
                <p style="margin: 0.25rem 0 0 0; font-size: 0.85rem; color: #64748b;">
                    Kelola dan pantau seluruh prompt video yang digenerate oleh AI untuk promosi objek wisata Anda.
                </p>
            </div>
            <div>
                <a href="{{ route('draft-prompt.index') }}" class="btn btn-secondary btn-sm">Buka Draft Promosi &rarr;</a>
            </div>
        </div>
    </div>

    <h3>Data User &amp; Agensi (JSON Frontend Reference)</h3>
    <pre>{{ json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>

    <div style="margin-top: 1.5rem;">
        @include('dashboard._logout')
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if there are any elements indicating a pending AI process
            const pendingElements = document.querySelectorAll('.ai-pending-indicator');

            if (pendingElements.length > 0) {
                // If there's a pending video, refresh the page every 10 seconds to check for updates
                setTimeout(function() {
                    window.location.reload();
                }, 10000);
            }
        });
    </script>
    @endpush
@endsection
