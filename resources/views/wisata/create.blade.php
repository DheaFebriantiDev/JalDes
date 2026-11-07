@extends('layouts.app')
@section('title', 'Ajukan Objek Wisata Baru')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Ajukan Objek Wisata Baru</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Sebagai agensi, daftarkan objek wisata Anda. Objek wisata akan dikirimkan ke Kepala Desa wilayah setempat untuk diverifikasi.
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

    <form method="POST" action="{{ route('wisata.store') }}" style="margin-top: 1rem;">
        @csrf

        <label for="nama_wisata">Nama Objek Wisata *</label>
        <input type="text" id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata') }}" required placeholder="Contoh: Glamping Lakeside Rancabali">
        @error('nama_wisata') <div class="error">{{ $message }}</div> @enderror

        <label for="jalan">Alamat / Jalan *</label>
        <input type="text" id="jalan" name="jalan" value="{{ old('jalan') }}" required placeholder="Contoh: Jl. Raya Ciwidey Situ Patenggang Km. 39">
        @error('jalan') <div class="error">{{ $message }}</div> @enderror

        <label for="kode_pos">Pilih Wilayah Desa (Kode Pos) *</label>
        <select id="kode_pos" name="kode_pos" required>
            <option value="">-- Pilih Wilayah Desa --</option>
            @foreach($desaList as $d)
                <option value="{{ $d->kode_pos }}" {{ old('kode_pos') == $d->kode_pos ? 'selected' : '' }}>
                    {{ $d->nama_desa }} (Kec. {{ $d->kecamatan }}, Pos: {{ $d->kode_pos }}) - Kades: {{ $d->kepalaDesa?->nama ?? 'Belum ada kades' }}
                </option>
            @endforeach
        </select>
        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
            Kepala Desa yang menjabat di desa ini akan otomatis ditunjuk sebagai verifikator wisata.
        </small>
        @error('kode_pos') <div class="error">{{ $message }}</div> @enderror

        <label for="video_promosi">URL / Path Video Promosi *</label>
        <input type="text" id="video_promosi" name="video_promosi" value="{{ old('video_promosi') }}" required placeholder="Contoh: videos/promosi/glamping-lakeside.mp4">
        @error('video_promosi') <div class="error">{{ $message }}</div> @enderror

        <label for="gambar">URL / Path Gambar Utama *</label>
        <input type="text" id="gambar" name="gambar" value="{{ old('gambar') }}" required placeholder="Contoh: images/wisata/glamping-lakeside.jpg">
        @error('gambar') <div class="error">{{ $message }}</div> @enderror

        <label for="video">URL / Path Video Full *</label>
        <input type="text" id="video" name="video" value="{{ old('video') }}" required placeholder="Contoh: videos/wisata/glamping-lakeside.mp4">
        @error('video') <div class="error">{{ $message }}</div> @enderror

        <label for="deskripsi">Deskripsi Wisata</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Jelaskan daya tarik, fasilitas, dan keunikan objek wisata...">{{ old('deskripsi') }}</textarea>
        @error('deskripsi') <div class="error">{{ $message }}</div> @enderror

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem;">Ajukan Wisata</button>
    </form>
@endsection

