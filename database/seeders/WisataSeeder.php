<?php

namespace Database\Seeders;

use App\Models\Agensi;
use App\Models\Desa;
use App\Models\KepalaDesa;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wisataList = [
            // 1. Kawah Putih
            [
                'nama_wisata'   => 'Kawah Putih',
                'jalan'         => 'Jl. Raya Ciwidey Patengan Km 11',
                'video_promosi' => null,
                'gambar'        => 'public/wisata/gambar/kawah-putih.png',
                'video'         => 'public/wisata/video/kawah-putih.mp4',
                'deskripsi'     => 'Kawah Putih merupakan sebuah danau kawah vulkanik yang terbentuk dari letusan Gunung Patuha dengan air danau berwarna putih kehijauan yang memukau. Dikelilingi hutan mati dan kabut putih alami, destinasi ini menawarkan udara sejuk pegunungan serta panorama alam yang sangat eksotis bagi para wisatawan.',
                'status'        => 'Buka',
                'kode_pos'      => 40972,
                'agensi_id'     => 'a1b2c3d4-0001-4000-8000-000000000001',
                'nipd'          => '3204050001',
                'desa'          => [
                    'kode_pos'  => 40972,
                    'nama_desa' => 'Sugihmukti',
                    'provinsi'  => 'Jawa Barat',
                    'kota'      => 'Kabupaten Bandung',
                    'kecamatan' => 'Pasirjambu',
                    'nipd'      => '3204050001',
                ],
                'agensi'        => [
                    'id'            => 'a1b2c3d4-0001-4000-8000-000000000001',
                    'nomor_telepon' => '081234567890',
                    'nama_agensi'   => 'Bandung Explore Tour',
                    'deskripsi'     => 'Agensi wisata khusus area Bandung Raya.',
                    'npwp'          => '01.234.567.8-401.000',
                    'user_id'       => 2,
                ],
                'kepala_desa'   => [
                    'nipd'          => '3204050001',
                    'nik'           => '3204051203750001',
                    'nama'          => 'H. Asep Saepudin',
                    'nomor_telepon' => '081223344551',
                    'status'        => true,
                    'user_id'       => 5,
                ],
            ],

            // 2. Ranca Upas
            [
                'nama_wisata'   => 'Ranca Upas',
                'jalan'         => 'Jl. Raya Ciwidey - Patengan No. KM. 11',
                'video_promosi' => null,
                'gambar'        => 'public/wisata/gambar/ranca-upas.png',
                'video'         => 'public/wisata/video/ranca-upas.mp4',
                'deskripsi'     => 'Kampung Cai Ranca Upas adalah kawasan bumi perkemahan dan penangkaran rusa yang terletak di dataran tinggi Ciwidey dengan suhu udara yang sangat sejuk. Wisatawan dapat berinteraksi langsung memberi makan kawanan rusa Jawa, berkemah di tengah alam terbuka, serta menikmati pemandian air panas alami.',
                'status'        => 'Buka',
                'kode_pos'      => 40973,
                'agensi_id'     => 'a1b2c3d4-0001-4000-8000-000000000001',
                'nipd'          => '3204060002',
                'desa'          => [
                    'kode_pos'  => 40973,
                    'nama_desa' => 'Patengan',
                    'provinsi'  => 'Jawa Barat',
                    'kota'      => 'Kabupaten Bandung',
                    'kecamatan' => 'Rancabali',
                    'nipd'      => '3204060002',
                ],
                'agensi'        => [
                    'id'            => 'a1b2c3d4-0001-4000-8000-000000000001',
                    'nomor_telepon' => '081234567890',
                    'nama_agensi'   => 'Bandung Explore Tour',
                    'deskripsi'     => 'Agensi wisata khusus area Bandung Raya.',
                    'npwp'          => '01.234.567.8-401.000',
                    'user_id'       => 2,
                ],
                'kepala_desa'   => [
                    'nipd'          => '3204060002',
                    'nik'           => '3204061508780002',
                    'nama'          => 'Jajang Kurniawan',
                    'nomor_telepon' => '081223344552',
                    'status'        => true,
                    'user_id'       => 6,
                ],
            ],

            // 3. Gunung Tangkuban Parahu
            [
                'nama_wisata'   => 'Gunung Tangkuban Parahu',
                'jalan'         => 'Jl. Raya Tangkuban Parahu No. 282',
                'video_promosi' => null,
                'gambar'        => 'public/wisata/gambar/gunung-tangkuban-parahu.png',
                'video'         => 'public/wisata/video/gunung-tangkuban-parahu.mp4',
                'deskripsi'     => 'Gunung Tangkuban Parahu adalah gunung api aktif yang terkenal dengan kawah utamanya yang luas dan megah bernama Kawah Ratu. Menyuguhkan pemandangan kawah vulkanik yang mempesona serta legenda Sangkuriang yang melegenda, destinasi ini menjadi salah satu ikon wisata paling populer di Jawa Barat.',
                'status'        => 'Buka',
                'kode_pos'      => 40391,
                'agensi_id'     => 'a1b2c3d4-0001-4000-8000-000000000001',
                'nipd'          => '3217080003',
                'desa'          => [
                    'kode_pos'  => 40391,
                    'nama_desa' => 'Cikahuripan',
                    'provinsi'  => 'Jawa Barat',
                    'kota'      => 'Kabupaten Bandung Barat',
                    'kecamatan' => 'Lembang',
                    'nipd'      => '3217080003',
                ],
                'agensi'        => [
                    'id'            => 'a1b2c3d4-0001-4000-8000-000000000001',
                    'nomor_telepon' => '081234567890',
                    'nama_agensi'   => 'Bandung Explore Tour',
                    'deskripsi'     => 'Agensi wisata khusus area Bandung Raya.',
                    'npwp'          => '01.234.567.8-401.000',
                    'user_id'       => 2,
                ],
                'kepala_desa'   => [
                    'nipd'          => '3217080003',
                    'nik'           => '3217082005800003',
                    'nama'          => 'Dadang Suherman',
                    'nomor_telepon' => '081223344553',
                    'status'        => true,
                    'user_id'       => 7,
                ],
            ],

            // 4. Dusun Bambu
            [
                'nama_wisata'   => 'Dusun Bambu',
                'jalan'         => 'Jl. Kolonel Masturi KM. 11, Kertawangi',
                'video_promosi' => null,
                'gambar'        => 'public/wisata/gambar/dusun-bambu.png',
                'video'         => 'public/wisata/video/dusun-bambu.mp4',
                'deskripsi'     => 'Dusun Bambu merupakan sebuah ecotourism park di kaki Gunung Burangrang yang memadukan keindahan panorama alam pegunungan dengan arsitektur budaya tradisional Sunda. Destinasi wisata ramah keluarga ini menyediakan berbagai wahana rekreasi, danau buatan dengan perahu kano, serta pengalaman kuliner khas Sunda di tepi danau.',
                'status'        => 'Buka',
                'kode_pos'      => 40551,
                'agensi_id'     => 'a1b2c3d4-0001-4000-8000-000000000001',
                'nipd'          => '3217060004',
                'desa'          => [
                    'kode_pos'  => 40551,
                    'nama_desa' => 'Kertawangi',
                    'provinsi'  => 'Jawa Barat',
                    'kota'      => 'Kabupaten Bandung Barat',
                    'kecamatan' => 'Cisarua',
                    'nipd'      => '3217060004',
                ],
                'agensi'        => [
                    'id'            => 'a1b2c3d4-0001-4000-8000-000000000001',
                    'nomor_telepon' => '081234567890',
                    'nama_agensi'   => 'Bandung Explore Tour',
                    'deskripsi'     => 'Agensi wisata khusus area Bandung Raya.',
                    'npwp'          => '01.234.567.8-401.000',
                    'user_id'       => 2,
                ],
                'kepala_desa'   => [
                    'nipd'          => '3217060004',
                    'nik'           => '3217061806790004',
                    'nama'          => 'H. Cecep Mulyana',
                    'nomor_telepon' => '081223344554',
                    'status'        => true,
                    'user_id'       => 8,
                ],
            ],
        ];

        // 1. Bersihkan data dummy lama yang bentrok agar tidak melanggar foreign key & unique constraints
        $targetWisata = array_column($wisataList, 'nama_wisata');
        $targetKodePos = array_column($wisataList, 'kode_pos');
        $targetNipds = array_map(fn($item) => $item['kepala_desa']['nipd'], $wisataList);

        // Hapus draft prompt lama yang terkait wisata lama di luar target
        DB::table('draft_prompt')->whereNotIn('wisata_id', function ($query) use ($targetWisata) {
            $query->select('id')->from('wisata')->whereIn('nama_wisata', $targetWisata);
        })->delete();

        // Hapus wisata lama di luar target
        Wisata::whereNotIn('nama_wisata', $targetWisata)->delete();

        // Set null referensi nipd lama di tabel desa sebelum menghapus kepala desa lama
        DB::table('desa')->whereNotIn('nipd', $targetNipds)->update(['nipd' => null]);

        // Hapus desa lama di luar target
        Desa::whereNotIn('kode_pos', $targetKodePos)->delete();

        // Hapus kepala desa lama di luar target (mencegah bentrok unique constraint pada user_id / NIK)
        KepalaDesa::whereNotIn('nipd', $targetNipds)->delete();

        // 2. Insert atau Update data seeder baru
        foreach ($wisataList as $item) {
            // Memastikan data User tersedia jika seeder dijalankan secara independen
            User::firstOrCreate(
                ['id' => $item['agensi']['user_id']],
                [
                    'username' => 'agensi_' . $item['agensi']['user_id'],
                    'email'    => 'agensi' . $item['agensi']['user_id'] . '@jaldes.id',
                    'role'     => 'agensi',
                    'password' => Hash::make('password'),
                ]
            );

            User::firstOrCreate(
                ['id' => $item['kepala_desa']['user_id']],
                [
                    'username' => 'kd_' . $item['kepala_desa']['user_id'],
                    'email'    => 'kd' . $item['kepala_desa']['user_id'] . '@jaldes.id',
                    'role'     => 'kd',
                    'password' => Hash::make('password'),
                ]
            );

            // Simpan / Perbarui Agensi
            Agensi::updateOrCreate(
                ['id' => $item['agensi']['id']],
                [
                    'nomor_telepon' => $item['agensi']['nomor_telepon'],
                    'nama_agensi'   => $item['agensi']['nama_agensi'],
                    'deskripsi'     => $item['agensi']['deskripsi'],
                    'npwp'          => $item['agensi']['npwp'],
                    'user_id'       => $item['agensi']['user_id'],
                ]
            );

            // Simpan / Perbarui Kepala Desa (harus sebelum Desa karena desa.nipd mengacu ke kepala_desa.nipd)
            KepalaDesa::updateOrCreate(
                ['nipd' => $item['kepala_desa']['nipd']],
                [
                    'nik'           => $item['kepala_desa']['nik'],
                    'nama'          => $item['kepala_desa']['nama'],
                    'nomor_telepon' => $item['kepala_desa']['nomor_telepon'],
                    'status'        => $item['kepala_desa']['status'],
                    'user_id'       => $item['kepala_desa']['user_id'],
                ]
            );

            // Simpan / Perbarui Desa
            Desa::updateOrCreate(
                ['kode_pos' => $item['desa']['kode_pos']],
                [
                    'nama_desa' => $item['desa']['nama_desa'],
                    'provinsi'  => $item['desa']['provinsi'],
                    'kota'      => $item['desa']['kota'],
                    'kecamatan' => $item['desa']['kecamatan'],
                    'nipd'      => $item['desa']['nipd'],
                ]
            );

            // Simpan / Perbarui Wisata
            Wisata::updateOrCreate(
                ['nama_wisata' => $item['nama_wisata']],
                [
                    'jalan'         => $item['jalan'],
                    'video_promosi' => $item['video_promosi'],
                    'gambar'        => $item['gambar'],
                    'video'         => $item['video'],
                    'deskripsi'     => $item['deskripsi'],
                    'status'        => is_bool($item['status']) ? $item['status'] : ($item['status'] === 'Buka'),
                    'kode_pos'      => $item['kode_pos'],
                    'agensi_id'     => $item['agensi_id'],
                    'nipd'          => $item['nipd'],
                ]
            );
        }
    }
}
