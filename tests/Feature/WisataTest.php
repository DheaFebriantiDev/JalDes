<?php

use App\Jobs\GeneratePromotionalVideo;
use App\Models\Agensi;
use App\Models\Desa;
use App\Models\DraftPrompt;
use App\Models\KepalaDesa;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->replicateStartResponse = [
        'id' => 'pred_12345',
        'urls' => [
            'get' => 'https://api.replicate.com/v1/predictions/pred_12345',
        ],
    ];
    $this->replicateStartStatus = 201;
    $this->replicatePollResponse = [
        'status' => 'succeeded',
        'output' => 'https://replicate.delivery/pbxt/generated-video.mp4',
    ];

    Http::fake(function ($request) {
        if (str_contains($request->url(), 'api.replicate.com/v1/') && $request->method() === 'POST') {
            return Http::response($this->replicateStartResponse, $this->replicateStartStatus);
        }
        if (str_contains($request->url(), 'api.replicate.com/v1/predictions/')) {
            return Http::response($this->replicatePollResponse, 200);
        }

        return Http::response([], 200);
    });

    // Setup Kades & Desa
    $this->kdUser = User::factory()->create(['role' => 'kd']);
    $this->kades = KepalaDesa::create([
        'nipd' => 'KADES-LEMBANG', 'nik' => 'NIK-001', 'nama' => 'Pak Kades Lembang',
        'nomor_telepon' => '08123', 'status' => true, 'user_id' => $this->kdUser->id,
    ]);

    $this->desa = Desa::create([
        'kode_pos' => 40391, 'nama_desa' => 'Cibodas', 'kecamatan' => 'Lembang',
        'kota' => 'Bandung Barat', 'provinsi' => 'Jawa Barat', 'nipd' => $this->kades->nipd,
    ]);

    // Setup Agensi
    $this->agensiUser = User::factory()->create(['role' => 'agensi']);
    $this->agensi = Agensi::create([
        'nomor_telepon' => '08999',
        'nama_agensi' => 'Lembang Tour',
        'user_id' => $this->agensiUser->id,
    ]);
});

test('agensi can create new wisata and kades is automatically assigned', function () {
    $response = $this->actingAs($this->agensiUser)->post('/wisata', [
        'nama_wisata' => 'Bukit Bintang Lembang',
        'jalan' => 'Jl. Bukit Bintang No. 1',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/bukit.mp4',
        'gambar' => 'images/wisata/bukit.jpg',
        'video' => 'videos/wisata/bukit.mp4',
        'deskripsi' => 'Pemandangan malam kota Bandung.',
    ]);

    $response->assertRedirect(route('wisata.index'));

    $wisata = Wisata::where('nama_wisata', 'Bukit Bintang Lembang')->first();
    expect($wisata)->not->toBeNull();
    expect($wisata->agensi_id)->toBe($this->agensi->id);
    expect($wisata->kode_pos)->toBe($this->desa->kode_pos);
    expect($wisata->nipd)->toBe($this->kades->nipd); // Kades otomatis ditunjuk
    expect($wisata->status)->toBeNull(); // Default pending verifikasi
});

test('kepala desa can verify and approve wisata in their village', function () {
    $wisata = Wisata::create([
        'nama_wisata' => 'Tangkuban Parahu',
        'jalan' => 'Jl. Tangkuban Parahu',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/tangkuban.mp4',
        'gambar' => 'images/wisata/tangkuban.jpg',
        'video' => 'videos/wisata/tangkuban.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    // Kades memverifikasi dan menyetujui wisata
    $response = $this->actingAs($this->kdUser)->post("/wisata/{$wisata->id}/verifikasi", [
        'status' => 'approve',
    ]);

    $response->assertRedirect();
    expect($wisata->fresh()->status)->toBeTrue();
});

test('kepala desa can reject wisata in their village', function () {
    $wisata = Wisata::create([
        'nama_wisata' => 'Tangkuban Parahu',
        'jalan' => 'Jl. Tangkuban Parahu',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/tangkuban.mp4',
        'gambar' => 'images/wisata/tangkuban.jpg',
        'video' => 'videos/wisata/tangkuban.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    // Kades menolak wisata
    $response = $this->actingAs($this->kdUser)->post("/wisata/{$wisata->id}/verifikasi", [
        'status' => 'reject',
    ]);

    $response->assertRedirect();
    expect($wisata->fresh()->status)->toBeFalse();
});

test('kades from another village cannot verify wisata outside their village', function () {
    $otherKdUser = User::factory()->create(['role' => 'kd']);
    $otherKades = KepalaDesa::create([
        'nipd' => 'KADES-OTHER', 'nik' => 'NIK-OTHER', 'nama' => 'Pak Kades Lain',
        'nomor_telepon' => '08777', 'status' => true, 'user_id' => $otherKdUser->id,
    ]);

    $wisata = Wisata::create([
        'nama_wisata' => 'Tangkuban Parahu',
        'jalan' => 'Jl. Tangkuban Parahu',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/tangkuban.mp4',
        'gambar' => 'images/wisata/tangkuban.jpg',
        'video' => 'videos/wisata/tangkuban.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd, // Desa milik kades Lembang, bukan kades Lain
    ]);

    // Kades lain mencoba memverifikasi -> 403 Forbidden
    $this->actingAs($otherKdUser)->post("/wisata/{$wisata->id}/verifikasi", [
        'status' => 'approve',
    ])->assertForbidden();

    expect($wisata->fresh()->status)->toBeNull();
});

test('public api wisata only returns approved wisata by default', function () {
    // Wisata disetujui
    Wisata::create([
        'nama_wisata' => 'Wisata Disetujui',
        'jalan' => 'Jl. Wisata 1',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'v1.mp4', 'gambar' => 'g1.jpg', 'video' => 'v1.mp4',
        'status' => true,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    // Wisata pending
    Wisata::create([
        'nama_wisata' => 'Wisata Pending',
        'jalan' => 'Jl. Wisata 2',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'v2.mp4', 'gambar' => 'g2.jpg', 'video' => 'v2.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    $response = $this->getJson('/api/wisata');
    $response->assertOk();
    $data = $response->json('data');

    expect(count($data))->toBe(1);
    expect($data[0]['nama_wisata'])->toBe('Wisata Disetujui');
});

test('agensi can create wisata with ai mode and automatically generates draft prompt record', function () {
    $response = $this->actingAs($this->agensiUser)->post('/wisata', [
        'nama_wisata' => 'Kawah Putih Ciwidey',
        'jalan' => 'Jl. Situ Patenggang Km. 11',
        'kode_pos' => $this->desa->kode_pos,
        'gambar' => 'images/wisata/kawah.jpg',
        'video' => 'videos/wisata/kawah-full.mp4',
        'deskripsi' => 'Danau kawah vulkanik berwarna putih kehijauan.',
        'mode_promosi' => 'ai',
        'prompt_keterangan' => 'Drone shot golden hour kabut tipis sinematik 4K.',
    ]);

    $response->assertRedirect(route('wisata.index'));

    $wisata = Wisata::where('nama_wisata', 'Kawah Putih Ciwidey')->first();
    expect($wisata)->not->toBeNull();
    expect($wisata->draftPrompt)->not->toBeNull();
    expect($wisata->draftPrompt->agensi_id)->toBe($this->agensi->id);
    expect($wisata->draftPrompt->prompt_message)->toContain('Drone shot golden hour');
    expect($wisata->video_promosi)->toContain('replicate.delivery');
});

test('agensi can create wisata with manual mode', function () {
    $response = $this->actingAs($this->agensiUser)->post('/wisata', [
        'nama_wisata' => 'Curug Maribaya',
        'jalan' => 'Jl. Maribaya No. 105',
        'kode_pos' => $this->desa->kode_pos,
        'gambar' => 'images/wisata/maribaya.jpg',
        'video' => 'videos/wisata/maribaya-full.mp4',
        'deskripsi' => 'Air terjun alami berhawa dingin.',
        'mode_promosi' => 'manual',
        'video_promosi_manual' => 'videos/promosi/maribaya-manual.mp4',
    ]);

    $response->assertRedirect(route('wisata.index'));

    $wisata = Wisata::where('nama_wisata', 'Curug Maribaya')->first();
    expect($wisata)->not->toBeNull();
    expect($wisata->video_promosi)->toBe('videos/promosi/maribaya-manual.mp4');
});

test('agensi can upload actual files for gambar, video, and video_manual', function () {
    Storage::fake('local');

    $gambarFile = UploadedFile::fake()->image('wisata.jpg');
    $videoFile = UploadedFile::fake()->create('dokumentasi.mp4', 500, 'video/mp4');
    $videoManualFile = UploadedFile::fake()->create('promosi.mp4', 300, 'video/mp4');

    $response = $this->actingAs($this->agensiUser)->post('/wisata', [
        'nama_wisata' => 'Situ Patenggang',
        'jalan' => 'Jl. Raya Ciwidey Rancabali',
        'kode_pos' => $this->desa->kode_pos,
        'gambar' => $gambarFile,
        'video' => $videoFile,
        'pilihan_promosi' => 'manual',
        'video_manual' => $videoManualFile,
        'deskripsi' => 'Danau eksotis di kawasan Rancabali.',
    ]);

    $response->assertRedirect(route('wisata.index'));

    $wisata = Wisata::where('nama_wisata', 'Situ Patenggang')->first();
    expect($wisata)->not->toBeNull();
    expect($wisata->gambar)->toStartWith('public/wisata/gambar/');
    expect($wisata->video)->toStartWith('public/wisata/video/');
    expect($wisata->video_promosi)->toStartWith('public/wisata/promosi/');
});

test('GeneratePromotionalVideo job constructs anti-hallucination prompt and sends to fal.ai', function () {
    $wisata = Wisata::create([
        'nama_wisata' => 'Ranca Upas',
        'jalan' => 'Jl. Raya Ciwidey Patenggang Km. 11',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'pending.mp4',
        'gambar' => 'public/wisata/gambar/ranca.jpg',
        'video' => 'public/wisata/video/ranca.mp4',
        'deskripsi' => 'Bumi perkemahan dan penangkaran rusa di Ciwidey.',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    $draft = DraftPrompt::create([
        'timestamp' => now(),
        'prompt_message' => 'Tampilkan rusa di pagi berkabut dengan sinar matahari menembus pepohonan.',
        'wisata_id' => $wisata->id,
        'agensi_id' => $this->agensi->id,
    ]);

    $job = new GeneratePromotionalVideo($wisata, $draft);
    $job->handle();

    $expectedPrompt = 'cinematic drone video of tourism spot, Ranca Upas, Bumi perkemahan dan penangkaran rusa di Ciwidey., Tampilkan rusa di pagi berkabut dengan sinar matahari menembus pepohonan.';

    Http::assertSent(function ($request) use ($expectedPrompt) {
        return $request->method() === 'POST'
            && ($request['input']['prompt'] ?? null) === $expectedPrompt;
    });

    expect($wisata->fresh()->video_promosi)->toBe('https://replicate.delivery/pbxt/generated-video.mp4');
});

test('GeneratePromotionalVideo job re-throws exception and updates wisata status to false when api fails', function () {
    $this->replicateStartResponse = 'Replicate API Error';
    $this->replicateStartStatus = 500;

    $wisata = Wisata::create([
        'nama_wisata' => 'Wisata Gagal AI',
        'jalan' => 'Jl. Gagal 123',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => null,
        'gambar' => 'public/wisata/gambar/gagal.jpg',
        'video' => 'public/wisata/video/gagal.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    $draft = DraftPrompt::create([
        'timestamp' => now(),
        'prompt_message' => 'Prompt yang akan gagal.',
        'wisata_id' => $wisata->id,
        'agensi_id' => $this->agensi->id,
    ]);

    $job = new GeneratePromotionalVideo($wisata, $draft);

    $exceptionThrown = false;
    try {
        $job->handle();
    } catch (\Throwable $e) {
        $exceptionThrown = true;
    }

    expect($exceptionThrown)->toBeTrue();
    expect($wisata->fresh()->status)->toBeFalse();
});

test('blade view video player dynamically handles external URLs and local storage paths', function () {
    $aiWisata = Wisata::create([
        'nama_wisata' => 'Wisata AI Video',
        'jalan' => 'Jl. AI 1',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'https://fal.media/files/monkey/ai-output.mp4',
        'gambar' => 'public/wisata/gambar/ai.jpg',
        'video' => 'public/wisata/video/ai.mp4',
        'status' => true,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    $manualWisata = Wisata::create([
        'nama_wisata' => 'Wisata Manual Video',
        'jalan' => 'Jl. Manual 2',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'wisata/promosi/manual-clip.mp4',
        'gambar' => 'public/wisata/gambar/manual.jpg',
        'video' => 'public/wisata/video/manual.mp4',
        'status' => true,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    $aiResponse = $this->actingAs($this->kdUser)->get(route('wisata.show', $aiWisata->id));
    $aiResponse->assertOk();
    $aiResponse->assertSee('src="https://fal.media/files/monkey/ai-output.mp4"', false);

    $manualResponse = $this->actingAs($this->kdUser)->get(route('wisata.show', $manualWisata->id));
    $manualResponse->assertOk();
    $manualResponse->assertSee('storage/wisata/promosi/manual-clip.mp4', false);
});

test('agency dashboard shows pending indicator and polling script when ai video is processing', function () {
    $pendingWisata = Wisata::create([
        'nama_wisata' => 'Wisata Proses AI',
        'jalan' => 'Jl. Pending 1',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => null,
        'gambar' => 'public/wisata/gambar/pending.jpg',
        'video' => 'public/wisata/video/pending.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    DraftPrompt::create([
        'timestamp' => now(),
        'prompt_message' => 'Prompt sedang diproses...',
        'wisata_id' => $pendingWisata->id,
        'agensi_id' => $this->agensi->id,
    ]);

    $response = $this->actingAs($this->agensiUser)->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('ai-pending-indicator');
    $response->assertSee('Memproses Video AI');
    $response->assertSee('window.location.reload()');
});

