@extends('layouts.app')
@section('title', 'Pendaftaran / Registrasi Agensi Baru')
@section('content')
    <div class="flex-between">
        <div>
            <h2>Pendaftaran Agensi Baru</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Daftarkan agensi wisata Anda beserta akun pengguna. Setiap user yang didaftarkan akan otomatis memiliki role <strong>agensi</strong>.
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

        <!-- 2. DATA PENGGUNA (USERS) - DYNAMIC REPEATER -->
        <div style="margin-top: 1.5rem; background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 6px;">
            <div class="flex-between">
                <div>
                    <h3 style="margin: 0;">2. Akun Pengguna Agensi (Minimal 1 User)</h3>
                    <p style="font-size: 0.85rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                        User pertama akan dijadikan akun utama (penanggung jawab). Anda dapat menambahkan lebih banyak user dengan tombol di bawah.
                    </p>
                </div>
                <button type="button" id="btnAddUser" class="btn btn-sm btn-success" style="margin: 0;">
                    + Tambah User Lainnya
                </button>
            </div>

            <div id="usersContainer" style="margin-top: 1rem;">
                <!-- Baris User 1 (Default / Utama) -->
                <div class="user-row" style="background: #fff; border: 1px solid #d1d5db; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                    <div class="flex-between">
                        <strong style="color: #1f2937;">User 1 (Akun Utama Penanggung Jawab)</strong>
                        <span class="badge badge-success">Role: Agensi</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-top: 0.75rem;">
                        <div>
                            <label style="margin-top: 0;">Username *</label>
                            <input type="text" name="users[0][username]" value="{{ old('users.0.username') }}" required placeholder="Username unik">
                        </div>
                        <div>
                            <label style="margin-top: 0;">Alamat Email *</label>
                            <input type="email" name="users[0][email]" value="{{ old('users.0.email') }}" required placeholder="email@agensi.com">
                        </div>
                        <div>
                            <label style="margin-top: 0;">Password (Min. 6) *</label>
                            <input type="password" name="users[0][password]" required placeholder="Password login">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem; padding: 0.75rem 1.75rem; font-size: 1rem;">
            Daftar Sebagai Agensi Baru
        </button>
    </form>

    <script>
        let userIndex = 1;

        document.getElementById('btnAddUser').addEventListener('click', function() {
            userIndex++;
            const container = document.getElementById('usersContainer');

            const div = document.createElement('div');
            div.className = 'user-row';
            div.style.background = '#fff';
            div.style.border = '1px solid #d1d5db';
            div.style.padding = '1rem';
            div.style.borderRadius = '6px';
            div.style.marginBottom = '1rem';
            div.setAttribute('data-index', userIndex);

            div.innerHTML = `
                <div class="flex-between">
                    <strong style="color: #1f2937;">User ${userIndex} (Anggota Agensi)</strong>
                    <div class="flex">
                        <span class="badge badge-gray">Role: Agensi</span>
                        <button type="button" class="btn btn-sm btn-danger btn-remove-user" style="margin: 0; padding: 0.15rem 0.5rem;">Hapus</button>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-top: 0.75rem;">
                    <div>
                        <label style="margin-top: 0;">Username *</label>
                        <input type="text" name="users[${userIndex - 1}][username]" required placeholder="Username unik">
                    </div>
                    <div>
                        <label style="margin-top: 0;">Alamat Email *</label>
                        <input type="email" name="users[${userIndex - 1}][email]" required placeholder="email@agensi.com">
                    </div>
                    <div>
                        <label style="margin-top: 0;">Password (Min. 6) *</label>
                        <input type="password" name="users[${userIndex - 1}][password]" required placeholder="Password login">
                    </div>
                </div>
            `;

            container.appendChild(div);

            div.querySelector('.btn-remove-user').addEventListener('click', function() {
                div.remove();
            });
        });
    </script>
@endsection

