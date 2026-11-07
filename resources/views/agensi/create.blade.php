@extends('layouts.app')
@section('title', 'Pendaftaran / Registrasi Agensi Baru')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Pendaftaran Agensi Baru</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Daftarkan agensi wisata Anda. Satu akun pengguna terhubung secara eksklusif dengan satu agensi (1 User = 1 Agensi).
            </p>
        </div>
        <a href="{{ url()->previous() == url()->current() ? route('login') : url()->previous() }}" class="btn btn-secondary">Kembali</a>
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

    <form method="POST" action="{{ route('agensi.store') }}" style="margin-top: 1rem;">
        @csrf

        <!-- 1. DATA PROFIL AGENSI -->
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <h3 style="margin-top: 0;">1. Profil Perusahaan / Agensi</h3>

            <label for="nama_agensi">Nama Agensi *</label>
            <input type="text" id="nama_agensi" name="nama_agensi" value="{{ old('nama_agensi') }}" required placeholder="Contoh: Pesona Alam Wisata">
            @error('nama_agensi') <div class="error">{{ $message }}</div> @enderror

            <label for="nomor_telepon">Nomor Telepon / WhatsApp Kantor *</label>
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required placeholder="Contoh: 08123456789">
            @error('nomor_telepon') <div class="error">{{ $message }}</div> @enderror

            <label for="npwp">NPWP Agensi (Opsional)</label>
            <input type="text" id="npwp" name="npwp" value="{{ old('npwp') }}" placeholder="Contoh: 01.234.567.8-123.000">
            @error('npwp') <div class="error">{{ $message }}</div> @enderror

            <label for="deskripsi">Deskripsi Agensi (Opsional)</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Profil singkat agensi tour & travel...">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <div class="error">{{ $message }}</div> @enderror
        </div>

        <!-- 2. DATA AKUN PENGGUNA (1 USER = 1 AGENSI) -->
        <div style="margin-top: 1.5rem; background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <div class="flex-between">
                <h3 style="margin: 0;">2. Akun Pengguna Agensi</h3>
                <span class="badge badge-success">Role: Agensi</span>
            </div>
            <p style="font-size: 0.85rem; color: #6b7280; margin: 0.25rem 0 1rem 0;">
                Akun ini akan otomatis memiliki role <strong>agensi</strong> dan bertindak sebagai pemilik akun agensi ini.
            </p>

            <label for="username">Username Akun *</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="Contoh: owner_pesona">
            @error('username') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Alamat Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: owner@pesonaalam.com">
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password (Minimal 6 Karakter) *</label>
            <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi">
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem; padding: 0.75rem 1.75rem; font-size: 1rem;">
            Daftar Sebagai Agensi Baru
        </button>
    </form>
@endsection
