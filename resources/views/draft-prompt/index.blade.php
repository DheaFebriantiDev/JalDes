@extends('layouts.app')
@section('title', 'Draft Prompt Promosi')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Draft Prompt Promosi AI</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Kelola prompt promosi video untuk objek wisata agensi Anda.
            </p>
        </div>
        <div>
            @if(auth()->user()?->isAgensi() || auth()->user()?->isAdmin())
                <a href="{{ route('draft-prompt.create') }}" class="btn btn-success">+ Buat Prompt Baru</a>
            @endif
            <a href="{{ route('draft-prompt.index', ['format' => 'json'] + request()->query()) }}" target="_blank" class="btn btn-secondary">Output JSON</a>
        </div>
    </div>

    <form method="GET" action="{{ route('draft-prompt.index') }}" style="margin-top: 1rem;">
        <div class="flex">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari prompt atau nama wisata..." style="margin: 0; flex: 1;">
            <button type="submit" style="margin: 0;">Cari</button>
            @if($search)
                <a href="{{ route('draft-prompt.index') }}" class="btn btn-secondary" style="margin: 0;">Reset</a>
            @endif
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>Objek Wisata</th>
                <th>Isi Prompt Promosi</th>
                <th>Waktu Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($drafts as $draft)
                <tr>
                    <td>
                        <strong>{{ $draft->wisata?->nama_wisata ?? '(Wisata Tidak Ditemukan)' }}</strong><br>
                        <small style="color: #6b7280;">{{ $draft->wisata?->jalan }}</small>
                    </td>
                    <td>
                        <em>"{{ $draft->prompt_message }}"</em>
                    </td>
                    <td>
                        {{ $draft->timestamp?->translatedFormat('d M Y, H:i') ?? '-' }}
                    </td>
                    <td>
                        <div class="flex">
                            <a href="{{ route('draft-prompt.show', $draft->id) }}" class="btn btn-sm btn-secondary">Detail</a>
                            @if(auth()->user()?->isAgensi() && auth()->user()->agensi?->id === $draft->agensi_id || auth()->user()?->isAdmin())
                                <form method="POST" action="{{ route('draft-prompt.destroy', $draft->id) }}" onsubmit="return confirm('Hapus draft prompt ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #6b7280; padding: 2rem;">
                        Belum ada draft prompt yang dibuat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 2rem;">
        <h3>Data JSON (AI Prompt Integration)</h3>
        <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
@endsection

