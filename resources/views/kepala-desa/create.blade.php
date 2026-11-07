@extends('layouts.app')
@section('title', 'Pendaftaran Kepala Desa Baru')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Pendaftaran Kepala Desa Baru</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Cukup masukkan Kode Pos wilayah desa. Jika desa sudah terdaftar, Anda hanya perlu mengisi data diri kades.
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

    <form method="POST" action="{{ route('kepala-desa.store') }}" style="margin-top: 1rem;">
        @csrf

        <!-- LANGKAH 1: KODE POS DESA -->
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <h3 style="margin-top: 0;">1. Wilayah Penugasan (Kode Pos)</h3>

            <label for="kode_pos">Masukkan Kode Pos Desa *</label>
            <div class="flex">
                <input type="number" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required placeholder="Contoh: 40391" style="flex: 1;" autocomplete="off">
                <button type="button" id="btnCekKodePos" class="btn btn-secondary" style="margin: 0;">Cek Desa</button>
            </div>
            @error('kode_pos') <div class="error">{{ $message }}</div> @enderror

            <!-- Info jika Desa Ditemukan -->
            <div id="desaFoundBox" style="display: none; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 1rem; border-radius: 6px; margin-top: 1rem;">
                <div style="color: #065f46; font-weight: 600;">✓ Desa Ditemukan:</div>
                <div id="desaFoundText" style="font-size: 0.9rem; color: #047857; margin-top: 0.25rem;"></div>
                <div id="kadesLamaWarning" style="display: none; margin-top: 0.5rem; font-size: 0.85rem; color: #b45309; background: #fffbeb; padding: 0.5rem; border-radius: 4px; border: 1px solid #fde68a;">
                    <strong>Perhatian:</strong> Desa ini saat ini dipimpin oleh <span id="kadesLamaNama"></span>. Jika Anda melanjutkan, kades lama tersebut akan otomatis <strong>dinonaktifkan</strong> statusnya dan digantikan oleh kades baru ini.
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

            <!-- FORM DESA BARU (Hanya muncul jika kode pos belum ada) -->
            <div id="formDesaBaru" style="display: {{ old('nama_desa') ? 'block' : 'none' }}; margin-top: 1.25rem; background: #fff; border: 1px dashed #3b82f6; padding: 1.25rem; border-radius: 6px;">
                <h4 style="margin-top: 0; color: #1d4ed8;">Data Wilayah Desa Baru:</h4>

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

        <!-- LANGKAH 2: DATA DIRI KEPALA DESA -->
        <div style="margin-top: 1.5rem; background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <h3 style="margin-top: 0;">2. Data Diri Kepala Desa</h3>

            <label for="nama">Nama Lengkap Kepala Desa *</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: H. Ahmad Supriyadi">
            @error('nama') <div class="error">{{ $message }}</div> @enderror

            <label for="nipd">NIPD (Nomor Induk Perangkat Desa) *</label>
            <input type="text" id="nipd" name="nipd" value="{{ old('nipd') }}" required placeholder="Contoh: 3204010099">
            @error('nipd') <div class="error">{{ $message }}</div> @enderror

            <label for="nik">NIK (Nomor Induk Kependudukan) *</label>
            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required placeholder="Contoh: 3204011234560099">
            @error('nik') <div class="error">{{ $message }}</div> @enderror

            <label for="nomor_telepon">Nomor Telepon / WhatsApp *</label>
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required placeholder="Contoh: 081234567890">
            @error('nomor_telepon') <div class="error">{{ $message }}</div> @enderror
        </div>

        <!-- LANGKAH 3: KREDENSIAL AKUN LOGIN (OPSIONAL) -->
        <div style="margin-top: 1.5rem; background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <h3 style="margin-top: 0;">3. Akun Login Kades (Opsional)</h3>
            <p style="font-size: 0.85rem; color: #6b7280;">Jika dikosongkan, sistem akan otomatis membuatkan username `kades_[NIPD]` dengan password default `password`.</p>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Otomatis jika dikosongkan">
            @error('username') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Otomatis jika dikosongkan">
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password (Minimal 6 Karakter)</label>
            <input type="password" id="password" name="password" placeholder="Default: password">
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem; padding: 0.75rem 1.5rem; font-size: 1rem;">
            Simpan &amp; Aktifkan Kepala Desa
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

        // Trigger on initial page load if kode_pos is already filled (e.g. from old input)
        window.addEventListener('DOMContentLoaded', checkKodePos);
    </script>
@endsection

