@extends('layouts.app')
@section('title', 'Edit Desa & Kepala Desa')
@section('content')
    <div class="flex-between">
        <h2>Edit Desa: {{ $desa->nama_desa }} ({{ $desa->kode_pos }})</h2>
        <a href="{{ route('desa.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <!-- Alert Algoritma Pergantian Kades -->
    <div class="alert alert-info" style="margin-top: 1rem;">
        <strong>Sistem Pergantian Kepala Desa:</strong><br>
        Jika Anda mengganti kepala desa di bawah ini, data kepala desa yang lama akan otomatis di-<strong>nonaktifkan</strong> (status = false), dan digantikan dengan data kepala desa yang baru dengan status <strong>aktif</strong> (status = true).
    </div>

    <form method="POST" action="{{ route('desa.update', $desa->kode_pos) }}" style="margin-top: 1rem;">
        @csrf
        @method('PUT')

        <h3>1. Informasi Wilayah Desa</h3>
        <p style="font-size: 0.85rem; color: #6b7280;">Kode Pos: <strong>{{ $desa->kode_pos }}</strong> (Primary Key tidak dapat diubah)</p>

        <label for="nama_desa">Nama Desa *</label>
        <input type="text" id="nama_desa" name="nama_desa" value="{{ old('nama_desa', $desa->nama_desa) }}" required>
        @error('nama_desa') <div class="error">{{ $message }}</div> @enderror

        <label for="kecamatan">Kecamatan *</label>
        <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $desa->kecamatan) }}" required>
        @error('kecamatan') <div class="error">{{ $message }}</div> @enderror

        <label for="kota">Kota / Kabupaten *</label>
        <input type="text" id="kota" name="kota" value="{{ old('kota', $desa->kota) }}" required>
        @error('kota') <div class="error">{{ $message }}</div> @enderror

        <label for="provinsi">Provinsi *</label>
        <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi', $desa->provinsi) }}" required>
        @error('provinsi') <div class="error">{{ $message }}</div> @enderror

        <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e5e7eb;">

        <h3>2. Kepala Desa Saat Ini &amp; Pergantian Kades</h3>
        <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; border: 1px solid #e5e7eb;">
            @if($desa->kepalaDesa)
                <p style="margin: 0;">
                    Kepala Desa Menjabat: <strong>{{ $desa->kepalaDesa->nama }}</strong> | NIPD: <code>{{ $desa->kepalaDesa->nipd }}</code> | Status: <span class="badge badge-success">Aktif</span>
                </p>
            @else
                <p style="margin: 0; color: #6b7280;">
                    Saat ini belum ada kepala desa yang terhubung dengan desa ini.
                </p>
            @endif
        </div>

        <label for="nipd">Pilih Kepala Desa Pengganti (Dari Data yang Ada)</label>
        <select id="nipd" name="nipd">
            <option value="">-- Kosongkan / Lepas Kades --</option>
            @foreach($allKades as $k)
                <option value="{{ $k->nipd }}" {{ old('nipd', $desa->nipd) == $k->nipd ? 'selected' : '' }}>
                    {{ $k->nama }} (NIPD: {{ $k->nipd }}) - {{ $k->status ? 'Sedang Aktif' : 'Non-aktif' }}
                    {{ $k->nipd === $desa->nipd ? '[Sedang Menjabat di Desa Ini]' : '' }}
                </option>
            @endforeach
        </select>
        @error('nipd') <div class="error">{{ $message }}</div> @enderror

        <div style="background: #fffbeb; padding: 1rem; border-radius: 6px; margin-top: 1.5rem; border: 1px dashed #f59e0b;">
            <strong style="color: #92400e;">Atau Daftarkan Kepala Desa Baru sebagai Pengganti:</strong>
            <p style="font-size: 0.85rem; color: #78350f; margin-top: 0.25rem;">
                Jika form kades baru di bawah ini diisi, sistem akan otomatis mendaftarkan kades baru ini, mengaktifkannya, dan menon-aktifkan kades lama.
            </p>

            <label for="new_kades_nipd">NIPD Baru</label>
            <input type="text" id="new_kades_nipd" name="new_kades_nipd" value="{{ old('new_kades_nipd') }}" placeholder="NIPD unik kades baru">
            @error('new_kades_nipd') <div class="error">{{ $message }}</div> @enderror

            <label for="new_kades_nik">NIK Baru</label>
            <input type="text" id="new_kades_nik" name="new_kades_nik" value="{{ old('new_kades_nik') }}" placeholder="NIK kades baru">
            @error('new_kades_nik') <div class="error">{{ $message }}</div> @enderror

            <label for="new_kades_nama">Nama Lengkap Kades Baru</label>
            <input type="text" id="new_kades_nama" name="new_kades_nama" value="{{ old('new_kades_nama') }}" placeholder="Nama kades baru">
            @error('new_kades_nama') <div class="error">{{ $message }}</div> @enderror

            <label for="new_kades_telepon">Nomor Telepon</label>
            <input type="text" id="new_kades_telepon" name="new_kades_telepon" value="{{ old('new_kades_telepon') }}">
            @error('new_kades_telepon') <div class="error">{{ $message }}</div> @enderror

            <label for="new_kades_username">Username Akun Login (Opsional)</label>
            <input type="text" id="new_kades_username" name="new_kades_username" value="{{ old('new_kades_username') }}" placeholder="Default: kades_[nipd]">
            @error('new_kades_username') <div class="error">{{ $message }}</div> @enderror

            <label for="new_kades_email">Email Akun Login (Opsional)</label>
            <input type="email" id="new_kades_email" name="new_kades_email" value="{{ old('new_kades_email') }}" placeholder="Default: kades_[nipd]@jaldes.id">
            @error('new_kades_email') <div class="error">{{ $message }}</div> @enderror

            <label for="new_kades_password">Password (Opsional, Default: password)</label>
            <input type="password" id="new_kades_password" name="new_kades_password">
            @error('new_kades_password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem;">Simpan Perubahan</button>
    </form>
@endsection

