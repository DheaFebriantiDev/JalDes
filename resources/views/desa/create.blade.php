@extends('layouts.app')
@section('title', 'Tambah Desa / Penugasan Kepala Desa')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Tambah Desa / Penugasan Kepala Desa</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Masukkan Kode Pos. Jika desa sudah ada, Anda tidak perlu mengisi data desa lagi, cukup input data kepala desa.
            </p>
        </div>
        <a href="{{ route('desa.index') }}" class="btn btn-secondary">Kembali</a>
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

    <form method="POST" action="{{ route('desa.store') }}" style="margin-top: 1rem;">
        @csrf

        <!-- 1. KODE POS & WILAYAH DESA -->
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <h3 style="margin-top: 0;">1. Wilayah Desa (Kode Pos)</h3>

            <label for="kode_pos">Masukkan Kode Pos *</label>
            <div class="flex">
                <input type="number" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required placeholder="Contoh: 40391" style="flex: 1;" autocomplete="off">
                <button type="button" id="btnCekKodePos" class="btn btn-secondary" style="margin: 0;">Cek Desa</button>
            </div>
            @error('kode_pos') <div class="error">{{ $message }}</div> @enderror

            <!-- Info jika Desa Sudah Terdaftar -->
            <div id="desaFoundBox" style="display: none; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 1rem; border-radius: 6px; margin-top: 1rem;">
                <div style="color: #065f46; font-weight: 600;">✓ Desa Ditemukan (Sudah Ada di Sistem):</div>
                <div id="desaFoundText" style="font-size: 0.9rem; color: #047857; margin-top: 0.25rem;"></div>
                <p style="font-size: 0.85rem; color: #065f46; margin: 0.5rem 0 0 0;">
                    <em>Wilayah desa sudah ada, Anda tidak perlu mengisi data wilayah desa lagi. Cukup lengkapi data kepala desa di bawah.</em>
                </p>

                <div id="kadesLamaWarning" style="display: none; margin-top: 0.75rem; font-size: 0.85rem; color: #b45309; background: #fffbeb; padding: 0.5rem; border-radius: 4px; border: 1px solid #fde68a;">
                    <strong>Perhatian:</strong> Desa ini saat ini dipimpin oleh <span id="kadesLamaNama"></span>. Jika kades baru ditugaskan, kades lama tersebut akan otomatis <strong>dinonaktifkan</strong> statusnya dan digantikan kades baru.
                </div>
            </div>

            <!-- Box jika Desa Belum Ada -->
            <div id="desaNotFoundBox" style="display: none; margin-top: 1rem;">
                <div class="alert alert-info" style="margin-bottom: 0.5rem;">
                    Desa dengan kode pos ini <strong>belum terdaftar</strong> dalam sistem.
                </div>
                <button type="button" id="btnToggleFormDesa" class="btn btn-success" style="margin-top: 0;">
                    + Kode Pos Belum Ada? Isi Form Wilayah Desa Baru
                </button>
            </div>

            <!-- Form Wilayah Desa Baru (hanya tampil jika desa belum ada) -->
            <div id="formDesaBaru" style="display: {{ old('nama_desa') ? 'block' : 'none' }}; margin-top: 1.25rem; background: #fff; border: 1px dashed #3b82f6; padding: 1.25rem; border-radius: 6px;">
                <h4 style="margin-top: 0; color: #1d4ed8;">Formulir Wilayah Desa Baru:</h4>

                <label for="nama_desa">Nama Desa *</label>
                <input type="text" id="nama_desa" name="nama_desa" value="{{ old('nama_desa') }}" placeholder="Contoh: Cibodas">
                @error('nama_desa') <div class="error">{{ $message }}</div> @enderror

                <label for="kecamatan">Kecamatan *</label>
                <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}" placeholder="Contoh: Lembang">
                @error('kecamatan') <div class="error">{{ $message }}</div> @enderror

                <label for="kota">Kota / Kabupaten *</label>
                <input type="text" id="kota" name="kota" value="{{ old('kota', 'Kabupaten Bandung Barat') }}">
                @error('kota') <div class="error">{{ $message }}</div> @enderror

                <label for="provinsi">Provinsi *</label>
                <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi', 'Jawa Barat') }}">
                @error('provinsi') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- 2. PENUGASAN KEPALA DESA -->
        <div style="margin-top: 1.5rem; background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <h3 style="margin-top: 0;">2. Penugasan Kepala Desa</h3>

            @if($availableKades->isNotEmpty())
                <label for="nipd">Opsi A: Pilih dari Kepala Desa yang Tersedia</label>
                <select id="nipd" name="nipd">
                    <option value="">-- Pilih Kepala Desa yang Tersedia --</option>
                    @foreach($availableKades as $k)
                        <option value="{{ $k->nipd }}" {{ old('nipd') == $k->nipd ? 'selected' : '' }}>
                            {{ $k->nama }} (NIPD: {{ $k->nipd }})
                        </option>
                    @endforeach
                </select>
                @error('nipd') <div class="error">{{ $message }}</div> @enderror
                <p style="font-size: 0.85rem; color: #6b7280; margin: 0.25rem 0 1rem 0;">Atau daftarkan kepala desa baru di bawah:</p>
            @endif

            <div style="background: #fff; padding: 1rem; border-radius: 6px; border: 1px dashed #d1d5db;">
                <strong style="color: #1f2937;">Opsi B: Daftarkan Kepala Desa Baru:</strong>

                <label for="kades_nama">Nama Lengkap Kades *</label>
                <input type="text" id="kades_nama" name="kades_nama" value="{{ old('kades_nama') }}" placeholder="Contoh: Budi Santoso">
                @error('kades_nama') <div class="error">{{ $message }}</div> @enderror

                <label for="kades_nipd">NIPD *</label>
                <input type="text" id="kades_nipd" name="kades_nipd" value="{{ old('kades_nipd') }}" placeholder="Contoh: 3204010009">
                @error('kades_nipd') <div class="error">{{ $message }}</div> @enderror

                <label for="kades_nik">NIK *</label>
                <input type="text" id="kades_nik" name="kades_nik" value="{{ old('kades_nik') }}" placeholder="Contoh: 3204011234560009">
                @error('kades_nik') <div class="error">{{ $message }}</div> @enderror

                <label for="kades_telepon">Nomor Telepon *</label>
                <input type="text" id="kades_telepon" name="kades_telepon" value="{{ old('kades_telepon') }}" placeholder="Contoh: 08123456789">
                @error('kades_telepon') <div class="error">{{ $message }}</div> @enderror

                <label for="kades_username">Username Akun Kades (Opsional)</label>
                <input type="text" id="kades_username" name="kades_username" value="{{ old('kades_username') }}" placeholder="Default: kades_[nipd]">
                @error('kades_username') <div class="error">{{ $message }}</div> @enderror

                <label for="kades_email">Email Akun Kades (Opsional)</label>
                <input type="email" id="kades_email" name="kades_email" value="{{ old('kades_email') }}" placeholder="Default: kades_[nipd]@jaldes.id">
                @error('kades_email') <div class="error">{{ $message }}</div> @enderror

                <label for="kades_password">Password (Opsional, Default: password)</label>
                <input type="password" id="kades_password" name="kades_password">
                @error('kades_password') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem; padding: 0.75rem 1.5rem; font-size: 1rem;">
            Simpan Data &amp; Aktifkan Kepala Desa
        </button>
    </form>

    <script>
        const desaDatabase = @json($desaList->keyBy('kode_pos'));

        function checkKodePos() {
            const inputVal = document.getElementById('kode_pos').value.trim();
            const foundBox = document.getElementById('desaFoundBox');
            const notFoundBox = document.getElementById('desaNotFoundBox');
            const foundText = document.getElementById('desaFoundText');
            const kadesWarn = document.getElementById('kadesLamaWarning');
            const kadesWarnNama = document.getElementById('kadesLamaNama');
            const formDesaBaru = document.getElementById('formDesaBaru');

            if (!inputVal) {
                foundBox.style.display = 'none';
                notFoundBox.style.display = 'none';
                return;
            }

            if (desaDatabase[inputVal]) {
                const d = desaDatabase[inputVal];
                foundText.innerHTML = `<strong>${d.nama_desa}</strong> - Kec. ${d.kecamatan}, ${d.kota}, ${d.provinsi}`;
                foundBox.style.display = 'block';
                notFoundBox.style.display = 'none';
                formDesaBaru.style.display = 'none';

                if (d.kepala_desa && d.kepala_desa.nama) {
                    kadesWarnNama.textContent = `${d.kepala_desa.nama} (NIPD: ${d.kepala_desa.nipd})`;
                    kadesWarn.style.display = 'block';
                } else {
                    kadesWarn.style.display = 'none';
                }
            } else {
                foundBox.style.display = 'none';
                notFoundBox.style.display = 'block';
            }
        }

        document.getElementById('kode_pos').addEventListener('input', checkKodePos);
        document.getElementById('btnCekKodePos').addEventListener('click', checkKodePos);

        document.getElementById('btnToggleFormDesa').addEventListener('click', function() {
            const formDesaBaru = document.getElementById('formDesaBaru');
            if (formDesaBaru.style.display === 'none') {
                formDesaBaru.style.display = 'block';
                this.textContent = 'Tutup Form Wilayah Desa';
            } else {
                formDesaBaru.style.display = 'none';
                this.textContent = '+ Kode Pos Belum Ada? Isi Form Wilayah Desa Baru';
            }
        });

        window.addEventListener('DOMContentLoaded', checkKodePos);
    </script>
@endsection
