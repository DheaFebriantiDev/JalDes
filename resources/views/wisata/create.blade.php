@extends('layouts.app')
@section('title', 'Tambah Objek Wisata Baru')
@section('content')
    <div class="flex-between" style="border-bottom: 2px solid #e5e7eb; padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
        <div>
            <h2 style="margin: 0;">Tambah Objek Wisata Baru</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                Daftarkan objek wisata agensi Anda, unggah materi media, dan rancang video promosi otomatis via AI (Fal.ai) atau manual.
            </p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Dashboard</a>
        </div>
    </div>

    {{-- Step Wizard Indicator --}}
    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.75rem; background: #f8fafc; padding: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
        <div style="flex: 1; padding: 0.5rem; background: #e0f2fe; color: #0369a1; border-radius: 6px; font-weight: 600; text-align: center;">
            1. Data Wisata
        </div>
        <div style="flex: 1; padding: 0.5rem; background: #e0f2fe; color: #0369a1; border-radius: 6px; font-weight: 600; text-align: center;">
            2. Upload Gambar &amp; Video Utama
        </div>
        <div style="flex: 1; padding: 0.5rem; background: #dcfce7; color: #15803d; border-radius: 6px; font-weight: 700; text-align: center; border: 1px solid #86efac;">
            3. Pembuatan Video Promosi (AI / Manual)
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <strong>Terdapat beberapa kesalahan:</strong>
            <ul style="margin: 0.25rem 0 0 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('wisata.store') }}" enctype="multipart/form-data" id="formWisata">
        @csrf

        {{-- ========================================== --}}
        {{-- LANGKAH 1: MASUKKAN DATA WISATA           --}}
        {{-- ========================================== --}}
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.5rem;">
                <span style="background: #2563eb; color: #fff; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.8rem; font-weight: bold;">1</span>
                <h3 style="margin: 0; font-size: 1.1rem; color: #1f2937;">Informasi Objek Wisata</h3>
            </div>

            <label for="nama_wisata">Nama Objek Wisata *</label>
            <input type="text" id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata') }}" required placeholder="Contoh: Kawah Putih Ciwidey">
            @error('nama_wisata') <div class="error">{{ $message }}</div> @enderror

            <label for="jalan">Alamat Lengkap / Jalan *</label>
            <input type="text" id="jalan" name="jalan" value="{{ old('jalan') }}" required placeholder="Contoh: Jl. Situ Patengan, Sugihmukti, Pasirjambu">
            @error('jalan') <div class="error">{{ $message }}</div> @enderror

            <label for="kode_pos">Wilayah Desa Penanggung Jawab (Kode Pos) *</label>
            <select id="kode_pos" name="kode_pos" required>
                <option value="">-- Pilih Wilayah Desa &amp; Kepala Desa --</option>
                @foreach($desaList as $d)
                    <option value="{{ $d->kode_pos }}" data-desa="{{ $d->nama_desa }}" data-kecamatan="{{ $d->kecamatan }}" {{ old('kode_pos') == $d->kode_pos ? 'selected' : '' }}>
                        {{ $d->nama_desa }} (Kec. {{ $d->kecamatan }}, Pos: {{ $d->kode_pos }}) &mdash; Kades: {{ $d->kepalaDesa?->nama ?? 'Belum ada' }}
                    </option>
                @endforeach
            </select>
            <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                Kepala Desa yang menjabat di desa ini otomatis menjadi verifikator perizinan objek wisata.
            </small>
            @error('kode_pos') <div class="error">{{ $message }}</div> @enderror

            <label for="deskripsi">Deskripsi Singkat Wisata</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Ceritakan daya tarik alam, keindahan, fasilitas, jam buka...">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <div class="error">{{ $message }}</div> @enderror
        </div>

        {{-- ========================================== --}}
        {{-- LANGKAH 2: UPLOAD GAMBAR DAN VIDEO        --}}
        {{-- ========================================== --}}
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.5rem;">
                <span style="background: #2563eb; color: #fff; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.8rem; font-weight: bold;">2</span>
                <h3 style="margin: 0; font-size: 1.1rem; color: #1f2937;">Unggah Media Utama Wisata</h3>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="gambar">Upload Gambar Utama *</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; background: #f9fafb; box-sizing: border-box;">
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                        Wajib diisi. Format gambar (JPG, PNG, WebP) yang menampilkan ikon wisata.
                    </small>
                    @error('gambar') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label for="video">Upload Video Full (Dokumentasi Mentah - Opsional)</label>
                    <input type="file" id="video" name="video" accept="video/*" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; background: #f9fafb; box-sizing: border-box;">
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                        Opsional. Video dokumentasi lengkap objek wisata.
                    </small>
                    @error('video') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- LANGKAH 3: BUAT VIDEO PROMOSI             --}}
        {{-- ========================================== --}}
        <div style="background: #ffffff; border: 2px solid #22c55e; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="background: #16a34a; color: #fff; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.8rem; font-weight: bold;">3</span>
                    <h3 style="margin: 0; font-size: 1.1rem; color: #15803d;">Pilihan Pembuatan Video Promosi</h3>
                </div>
                <span class="badge" style="background: #dcfce7; color: #166534; font-size: 0.8rem;">2 Opsi Tersedia</span>
            </div>

            <p style="color: #4b5563; font-size: 0.9rem; margin-top: 0; margin-bottom: 1rem;">
                Pilih metode pembuatan video promosi untuk objek wisata ini. Secara default, sistem menggunakan AI canggih Fal.ai untuk meng-generate video promosi otomatis.
            </p>

            {{-- Radio Cards Selector --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                {{-- Opsi 1: Generate AI (Fal.ai) --}}
                <label for="pilihan_ai" style="margin-top: 0; cursor: pointer; border: 2px solid #22c55e; background: #f0fdf4; border-radius: 8px; padding: 1rem; display: block; transition: all 0.2s;" id="label_mode_ai">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="pilihan_promosi" id="pilihan_ai" value="ai" {{ old('pilihan_promosi', 'ai') === 'ai' ? 'checked' : '' }} onchange="togglePromosiMode()">
                            <strong style="color: #166534; font-size: 1rem;">Buat Video dengan AI (Fal.ai)</strong>
                        </div>
                        <span class="badge badge-success">Default</span>
                    </div>
                    <p style="margin: 0; font-size: 0.85rem; color: #15803d; line-height: 1.4;">
                        Otomatis membuat video sinematik berkualitas tinggi berbasis konteks data wisata &amp; prompt custom.
                    </p>
                </label>

                {{-- Opsi 2: Upload Video Manual --}}
                <label for="pilihan_manual" style="margin-top: 0; cursor: pointer; border: 2px solid #e5e7eb; background: #f9fafb; border-radius: 8px; padding: 1rem; display: block; transition: all 0.2s;" id="label_mode_manual">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="pilihan_promosi" id="pilihan_manual" value="manual" {{ old('pilihan_promosi') === 'manual' ? 'checked' : '' }} onchange="togglePromosiMode()">
                            <strong style="color: #374151; font-size: 1rem;">Upload Video Manual</strong>
                        </div>
                        <span class="badge badge-gray">Keterbatasan File</span>
                    </div>
                    <p style="margin: 0; font-size: 0.85rem; color: #6b7280; line-height: 1.4;">
                        Unggah file video promosi buatan sendiri dengan batasan ukuran file, format spesifik (.mp4/.webm), dan resolusi.
                    </p>
                </label>
            </div>

            {{-- PANEL MODE AI (FAL.AI) --}}
            <div id="panel_ai" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <span style="font-size: 1.2rem;">✨</span>
                    <div>
                        <strong style="color: #0f172a;">Kustomisasi Draft Prompt AI (Fal.ai Media Engine)</strong>
                        <div style="font-size: 0.8rem; color: #64748b;">
                            Data ini akan disimpan ke tabel <code>draft_prompt</code> dan digabungkan dengan konteks data wisata saat dikirim ke Fal.ai.
                        </div>
                    </div>
                </div>

                <label for="prompt_keterangan" style="margin-top: 0.5rem;">Keterangan Tambahan / Custom Prompt untuk AI *</label>
                <textarea id="prompt_keterangan" name="prompt_keterangan" rows="3" style="width: 100%; padding: 0.6rem; border: 1px solid #94a3b8; border-radius: 6px; box-sizing: border-box; font-size: 0.9rem;" placeholder="Contoh: Drone shot golden hour dengan kabut tipis di atas kawah, gerakan kamera lambat sinematik 4K...">{{ old('prompt_keterangan') }}</textarea>
                <small style="color: #64748b; display: block; margin-top: 0.25rem;">
                    Anda dapat memasukkan detail gaya visual, angle kamera, suasana warna, atau nuansa khusus yang diinginkan.
                </small>
                @error('prompt_keterangan') <div class="error">{{ $message }}</div> @enderror

                {{-- Live Prompt Preview --}}
                <div style="margin-top: 1rem; background: #0f172a; color: #e2e8f0; padding: 0.85rem; border-radius: 6px; font-size: 0.85rem;">
                    <div style="color: #38bdf8; font-weight: 600; margin-bottom: 0.25rem; display: flex; justify-content: space-between;">
                        <span>Preview Final Prompt (Anti-Hallucination Context) untuk Fal.ai API:</span>
                        <span style="font-size: 0.75rem; color: #94a3b8;">Format: Context + Custom</span>
                    </div>
                    <div id="live_prompt_preview" style="font-family: monospace; white-space: pre-wrap; color: #a7f3d0;">Create a cinematic promotional video for this tourism spot. Name: [Nama Wisata]. Location: [Lokasi]. Description: [Deskripsi]. Additional details: [Keterangan Tambahan].</div>
                </div>
            </div>

            {{-- PANEL MODE MANUAL --}}
            <div id="panel_manual" style="display: none; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 1.25rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <span style="font-size: 1.2rem;">⚠️</span>
                    <div>
                        <strong style="color: #92400e;">Keterbatasan Upload Video Promosi Manual:</strong>
                        <ul style="margin: 0.25rem 0 0 0; padding-left: 1.25rem; font-size: 0.85rem; color: #78350f;">
                            <li>Format file wajib <strong>.mp4</strong> atau <strong>.webm</strong>.</li>
                            <li>Batas ukuran file maksimal <strong>50 MB</strong>.</li>
                            <li>Resolusi maksimal <strong>1080p (Full HD)</strong>, rasio 16:9 atau 9:16.</li>
                            <li>Durasi video promosi disarankan antara 15 hingga 60 detik.</li>
                        </ul>
                    </div>
                </div>

                <label for="video_manual" style="margin-top: 0.75rem;">Upload File Video Promosi Manual *</label>
                <input type="file" id="video_manual" name="video_manual" accept="video/mp4,video/webm" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #fde68a; border-radius: 4px; background: #ffffff; box-sizing: border-box;">
                <small style="color: #92400e; display: block; margin-top: 0.25rem;">
                    Pilih file video (.mp4 atau .webm) dari perangkat Anda.
                </small>
                @error('video_manual') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-success" style="font-size: 1rem; padding: 0.75rem 1.75rem; font-weight: 600;">
                Simpan &amp; Ajukan Wisata
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>

    <script>
        function togglePromosiMode() {
            const isAi = document.getElementById('pilihan_ai').checked;
            const panelAi = document.getElementById('panel_ai');
            const panelManual = document.getElementById('panel_manual');
            const labelAi = document.getElementById('label_mode_ai');
            const labelManual = document.getElementById('label_mode_manual');
            const manualInput = document.getElementById('video_manual');

            if (isAi) {
                panelAi.style.display = 'block';
                panelManual.style.display = 'none';
                labelAi.style.borderColor = '#22c55e';
                labelAi.style.backgroundColor = '#f0fdf4';
                labelManual.style.borderColor = '#e5e7eb';
                labelManual.style.backgroundColor = '#f9fafb';
                manualInput.removeAttribute('required');
                updatePromptPreview();
            } else {
                panelAi.style.display = 'none';
                panelManual.style.display = 'block';
                labelManual.style.borderColor = '#eab308';
                labelManual.style.backgroundColor = '#fefce8';
                labelAi.style.borderColor = '#e5e7eb';
                labelAi.style.backgroundColor = '#f9fafb';
                manualInput.setAttribute('required', 'required');
            }
        }

        function updatePromptPreview() {
            const nama = document.getElementById('nama_wisata').value.trim() || '[Nama Wisata]';
            const jalan = document.getElementById('jalan').value.trim() || '[Lokasi]';
            const deskripsi = document.getElementById('deskripsi').value.trim() || '[Deskripsi]';
            const custom = document.getElementById('prompt_keterangan').value.trim() || '[Keterangan Tambahan]';

            const preview = `Create a cinematic promotional video for this tourism spot. ` +
                            `Name: ${nama}. Location: ${jalan}. Description: ${deskripsi}. ` +
                            `Additional details: ${custom}.`;

            document.getElementById('live_prompt_preview').innerText = preview;
        }

        document.getElementById('nama_wisata').addEventListener('input', updatePromptPreview);
        document.getElementById('jalan').addEventListener('input', updatePromptPreview);
        document.getElementById('deskripsi').addEventListener('input', updatePromptPreview);
        document.getElementById('prompt_keterangan').addEventListener('input', updatePromptPreview);

        document.addEventListener('DOMContentLoaded', function() {
            togglePromosiMode();
            updatePromptPreview();
        });
    </script>
@endsection
