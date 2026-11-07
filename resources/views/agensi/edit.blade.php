@extends('layouts.app')
@section('title', 'Edit Agensi - ' . $agensi->nama_agensi)
@section('content')
    <div class="flex-between">
        <div>
            <h2>Edit Data Agensi</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Perbarui informasi agensi: <strong>{{ $agensi->nama_agensi }}</strong>
            </p>
        </div>
        <a href="{{ route('agensi.index') }}" class="btn btn-secondary">Kembali</a>
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

    <form method="POST" action="{{ route('agensi.update', $agensi->id) }}" style="margin-top: 1rem;">
        @csrf
        @method('PUT')

        <label for="nama_agensi">Nama Agensi *</label>
        <input type="text" id="nama_agensi" name="nama_agensi" value="{{ old('nama_agensi', $agensi->nama_agensi) }}" required>
        @error('nama_agensi') <div class="error">{{ $message }}</div> @enderror

        <label for="nomor_telepon">Nomor Telepon *</label>
        <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $agensi->nomor_telepon) }}" required>
        @error('nomor_telepon') <div class="error">{{ $message }}</div> @enderror

        <label for="npwp">NPWP Agensi</label>
        <input type="text" id="npwp" name="npwp" value="{{ old('npwp', $agensi->npwp) }}">
        @error('npwp') <div class="error">{{ $message }}</div> @enderror

        <label for="user_id">Pilih User Penanggung Jawab *</label>
        <select id="user_id" name="user_id" required>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('user_id', $agensi->user_id) == $u->id ? 'selected' : '' }}>
                    {{ $u->username }} ({{ $u->email }}) {{ $u->id == $agensi->user_id ? '[Saat Ini]' : '' }}
                </option>
            @endforeach
        </select>
        @error('user_id') <div class="error">{{ $message }}</div> @enderror

        <label for="deskripsi">Deskripsi Agensi</label>
        <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;">{{ old('deskripsi', $agensi->deskripsi) }}</textarea>
        @error('deskripsi') <div class="error">{{ $message }}</div> @enderror

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem;">Simpan Perubahan</button>
    </form>
@endsection

