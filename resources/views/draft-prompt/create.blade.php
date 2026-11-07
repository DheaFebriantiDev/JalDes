@extends('layouts.app')
@section('title', 'Buat Draft Prompt Promosi Baru')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Buat Draft Prompt Baru</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Tuliskan instruksi prompt untuk video promosi objek wisata Anda.
            </p>
        </div>
        <a href="{{ route('draft-prompt.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-top: 1rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('draft-prompt.store') }}" style="margin-top: 1rem;">
        @csrf

        <label for="wisata_id">Pilih Objek Wisata Terkait *</label>
        <select id="wisata_id" name="wisata_id" required>
            <option value="">-- Pilih Objek Wisata --</option>
            @foreach($wisataList as $w)
                <option value="{{ $w->id }}" {{ old('wisata_id') == $w->id ? 'selected' : '' }}>
                    {{ $w->nama_wisata }} ({{ $w->desa?->nama_desa ?? '-' }})
                </option>
            @endforeach
        </select>
        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
            Setiap objek wisata hanya dapat memiliki 1 draft prompt promosi aktif (relasi 1-to-1).
        </small>
        @error('wisata_id') <div class="error">{{ $message }}</div> @enderror

        <label for="prompt_message">Isi Pesan Prompt Promosi Video *</label>
        <textarea id="prompt_message" name="prompt_message" rows="5" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Contoh: Buatkan video promosi sinematik durasi 30 detik yang memperlihatkan pemandangan sunrise di Floating Market Lembang dengan musik gamelan modern...">{{ old('prompt_message') }}</textarea>
        @error('prompt_message') <div class="error">{{ $message }}</div> @enderror

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem;">Simpan Draft Prompt</button>
    </form>
@endsection

