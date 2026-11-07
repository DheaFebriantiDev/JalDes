@extends('layouts.app')
@section('title', 'Edit Objek Wisata - ' . $wisata->nama_wisata)
@section('content')
    <div class="flex-between">
        <div>
            <h2>Edit Objek Wisata</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Perbarui informasi objek wisata: <strong>{{ $wisata->nama_wisata }}</strong>
            </p>
        </div>
        <a href="{{ route('wisata.index') }}" class="btn btn-secondary">Kembali</a>
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

    <form method="POST" action="{{ route('wisata.update', $wisata->id) }}" style="margin-top: 1rem;">
        @csrf
        @method('PUT')

        <label for="nama_wisata">Nama Objek Wisata *</label>
        <input type="text" id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata', $wisata->nama_wisata) }}" required>
        @error('nama_wisata') <div class="error">{{ $message }}</div> @enderror

        <label for="jalan">Alamat / Jalan *</label>
        <input type="text" id="jalan" name="jalan" value="{{ old('jalan', $wisata->jalan) }}" required>
        @error('jalan') <div class="error">{{ $message }}</div> @enderror

        <label for="kode_pos">Wilayah Desa (Kode Pos) *</label>
        <select id="kode_pos" name="kode_pos" required>
            @foreach($desaList as $d)
                <option value="{{ $d->kode_pos }}" {{ old('kode_pos', $wisata->kode_pos) == $d->kode_pos ? 'selected' : '' }}>
                    {{ $d->nama_desa }} (Kec. {{ $d->kecamatan }}, Pos: {{ $d->kode_pos }}) - Kades: {{ $d->kepalaDesa?->nama ?? 'Belum ada kades' }}
                </option>
            @endforeach
        </select>
        @error('kode_pos') <div class="error">{{ $message }}</div> @enderror

        <label for="video_promosi">URL / Path Video Promosi *</label>
        <input type="text" id="video_promosi" name="video_promosi" value="{{ old('video_promosi', $wisata->video_promosi) }}" required>
        @error('video_promosi') <div class="error">{{ $message }}</div> @enderror

        <label for="gambar">URL / Path Gambar Utama *</label>
        <input type="text" id="gambar" name="gambar" value="{{ old('gambar', $wisata->gambar) }}" required>
        @error('gambar') <div class="error">{{ $message }}</div> @enderror

        <label for="video">URL / Path Video Full *</label>
        <input type="text" id="video" name="video" value="{{ old('video', $wisata->video) }}" required>
        @error('video') <div class="error">{{ $message }}</div> @enderror

        <label for="deskripsi">Deskripsi Wisata</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
        @error('deskripsi') <div class="error">{{ $message }}</div> @enderror

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem;">Simpan Perubahan</button>
    </form>
@endsection

