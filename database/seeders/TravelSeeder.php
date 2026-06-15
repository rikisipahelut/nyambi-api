<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TravelSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kategori baru ────────────────────────────────────────
        $now = now();

        DB::table('categories')->insert([
            ['id' => 'tour_guide',      'icon' => 'tour',                'title' => 'Pemandu Wisata',          'created_at' => $now],
            ['id' => 'travel_planner',  'icon' => 'travel_explore',      'title' => 'Travel Planner',          'created_at' => $now],
            ['id' => 'jastip',          'icon' => 'shopping_bag',        'title' => 'Jasa Titip (Jastip)',     'created_at' => $now],
            ['id' => 'teman_traveling', 'icon' => 'group',               'title' => 'Teman Traveling',         'created_at' => $now],
            ['id' => 'porter',          'icon' => 'luggage',             'title' => 'Porter & Angkat Koper',   'created_at' => $now],
            ['id' => 'ticketing',       'icon' => 'confirmation_number', 'title' => 'Jasa Ticketing & Hotel',  'created_at' => $now],
            ['id' => 'sewa_motor',      'icon' => 'two_wheeler',         'title' => 'Sewa Motor & Kendaraan',  'created_at' => $now],
        ]);

        // ── Pekerja traveling ────────────────────────────────────
        $password = Hash::make('password');

        $workers = [
            [
                'user'    => ['nama' => 'Reza Pramudya',    'email' => 'reza.guide@gmail.com',    'telepon' => '081122200001'],
                'profile' => [
                    'specialty'        => 'Tour Guide & Local Expert Bali',
                    'bio'              => 'Guide lokal Bali bersertifikat HPI (Himpunan Pramuwisata Indonesia). Fasih Bahasa Inggris, Jepang, dan Mandarin. Menguasai seluruh destinasi wisata Bali dari hidden gem sampai spot populer.',
                    'location'         => 'Bali',
                    'experience_years' => 9,
                    'response_time'    => '< 1 jam',
                    'completed_jobs'   => 534,
                    'rating'           => 4.97,
                    'status'           => 'available',
                ],
                'categories' => ['tour_guide', 'travel_planner'],
                'tags'       => ['Bersertifikat HPI', 'Multi-bahasa', 'Profesional', 'Berpengalaman'],
                'services'   => [
                    ['name' => 'Private Tour Bali Full Day',    'price' => 600000,  'unit' => 'per orang (8 jam)'],
                    ['name' => 'Private Tour Bali Half Day',    'price' => 350000,  'unit' => 'per orang (4 jam)'],
                    ['name' => 'Tour Sunrise Gunung Batur',     'price' => 450000,  'unit' => 'per orang'],
                    ['name' => 'Custom Itinerary Bali 3–7 hari','price' => 250000,  'unit' => 'per hari'],
                ],
            ],
            [
                'user'    => ['nama' => 'Ayu Rahmani',      'email' => 'ayu.planner@gmail.com',   'telepon' => '081122200002'],
                'profile' => [
                    'specialty'        => 'Travel Planner & Trip Organizer',
                    'bio'              => 'Spesialis perencanaan perjalanan ke seluruh Indonesia dan Asia Tenggara. Bantu urus itinerary, booking hotel, tiket pesawat, dan visa. Sudah membantu lebih dari 300 keluarga liburan tanpa ribet.',
                    'location'         => 'Jakarta Selatan',
                    'experience_years' => 6,
                    'response_time'    => '< 2 jam',
                    'completed_jobs'   => 312,
                    'rating'           => 4.92,
                    'status'           => 'available',
                ],
                'categories' => ['travel_planner', 'ticketing'],
                'tags'       => ['Harga Terbaik', 'Profesional', 'Responsif', 'All-inclusive'],
                'services'   => [
                    ['name' => 'Paket Itinerary Domestik',        'price' => 150000,  'unit' => 'per perjalanan'],
                    ['name' => 'Paket Itinerary Internasional',   'price' => 300000,  'unit' => 'per perjalanan'],
                    ['name' => 'Booking Tiket + Hotel (fee)',     'price' => 100000,  'unit' => 'per transaksi'],
                    ['name' => 'Konsultasi Perjalanan',           'price' => 75000,   'unit' => 'per sesi (1 jam)'],
                    ['name' => 'Urus Visa',                       'price' => 250000,  'unit' => 'per orang'],
                ],
            ],
            [
                'user'    => ['nama' => 'Dika Firmansyah',  'email' => 'dika.travel@gmail.com',   'telepon' => '081122200003'],
                'profile' => [
                    'specialty'        => 'Teman Traveling & Travel Photographer',
                    'bio'              => 'Siap jadi teman perjalanan seru ke mana saja! Berpengalaman solo trip ke 15 provinsi Indonesia. Sekaligus bisa dokumentasi foto & video perjalanan dengan kamera mirrorless. Cocok untuk solo traveler yang ingin ditemani dan diabadikan momen tripnya.',
                    'location'         => 'Yogyakarta',
                    'experience_years' => 4,
                    'response_time'    => '< 3 jam',
                    'completed_jobs'   => 87,
                    'rating'           => 4.89,
                    'status'           => 'available',
                ],
                'categories' => ['teman_traveling', 'foto_video'],
                'tags'       => ['Seru & Asyik', 'Fotografer', 'Solo Traveler Friendly', 'Fleksibel'],
                'services'   => [
                    ['name' => 'Teman Trip 1 Hari',              'price' => 300000,  'unit' => 'per hari'],
                    ['name' => 'Teman Trip Paket 3 Hari',        'price' => 800000,  'unit' => 'per paket'],
                    ['name' => 'Teman Trip + Foto (1 hari)',     'price' => 500000,  'unit' => 'per hari'],
                    ['name' => 'Dokumentasi Perjalanan (video)', 'price' => 750000,  'unit' => 'per hari'],
                ],
            ],
            [
                'user'    => ['nama' => 'Bagas Prasetya',   'email' => 'bagas.jastip@gmail.com',  'telepon' => '081122200004'],
                'profile' => [
                    'specialty'        => 'Jastip Korea, Jepang & Eropa',
                    'bio'              => 'Jastip terpercaya untuk produk Korea, Jepang, dan Eropa. Rutin berangkat 2x sebulan. Bisa request produk apapun: skincare, fashion, makanan, elektronik, merchandise idol. Harga transparan, barang 100% original.',
                    'location'         => 'Jakarta Pusat',
                    'experience_years' => 5,
                    'response_time'    => '< 4 jam',
                    'completed_jobs'   => 1243,
                    'rating'           => 4.85,
                    'status'           => 'available',
                ],
                'categories' => ['jastip'],
                'tags'       => ['Terpercaya', '100% Original', 'Harga Transparan', 'Berpengalaman'],
                'services'   => [
                    ['name' => 'Jastip Korea (fee)',    'price' => 30000,   'unit' => 'per item + 10% harga barang'],
                    ['name' => 'Jastip Jepang (fee)',   'price' => 35000,   'unit' => 'per item + 10% harga barang'],
                    ['name' => 'Jastip Eropa (fee)',    'price' => 50000,   'unit' => 'per item + 15% harga barang'],
                    ['name' => 'Titip Khusus / Custom', 'price' => 100000,  'unit' => 'per request (negosiasi)'],
                ],
            ],
            [
                'user'    => ['nama' => 'Rizky Mahendra',   'email' => 'rizky.porter@gmail.com',  'telepon' => '081122200005'],
                'profile' => [
                    'specialty'        => 'Porter & Jasa Angkat Koper Bandara',
                    'bio'              => 'Jasa porter profesional untuk bandara, stasiun, dan pelabuhan. Siap membantu lansia, ibu hamil, dan keluarga dengan banyak barang bawaan. Melayani area Jakarta, Surabaya, dan Bali.',
                    'location'         => 'Jakarta Pusat',
                    'experience_years' => 3,
                    'response_time'    => '< 1 jam',
                    'completed_jobs'   => 678,
                    'rating'           => 4.80,
                    'status'           => 'available',
                ],
                'categories' => ['porter', 'driver'],
                'tags'       => ['On Time', 'Ramah', 'Terpercaya', 'Harga Terjangkau'],
                'services'   => [
                    ['name' => 'Porter Bandara (per perjalanan)', 'price' => 100000,  'unit' => 'per orang'],
                    ['name' => 'Porter Stasiun Kereta',           'price' => 75000,   'unit' => 'per orang'],
                    ['name' => 'Antar Jemput Bandara + Porter',   'price' => 250000,  'unit' => 'per trip'],
                    ['name' => 'Angkat & Titip Koper (storage)',  'price' => 50000,   'unit' => 'per koper/hari'],
                ],
            ],
            [
                'user'    => ['nama' => 'Sinta Dewi',       'email' => 'sinta.tiket@gmail.com',   'telepon' => '081122200006'],
                'profile' => [
                    'specialty'        => 'Jasa Ticketing, Hotel & Visa',
                    'bio'              => 'Agen perjalanan independen berpengalaman 7 tahun. Spesialis cari tiket murah, hotel terbaik sesuai budget, dan urus visa berbagai negara. Sudah membantu ribuan traveler mendapatkan harga terbaik.',
                    'location'         => 'Surabaya',
                    'experience_years' => 7,
                    'response_time'    => '< 2 jam',
                    'completed_jobs'   => 2187,
                    'rating'           => 4.88,
                    'status'           => 'available',
                ],
                'categories' => ['ticketing', 'travel_planner'],
                'tags'       => ['Harga Terbaik', 'Responsif', 'Berpengalaman', 'All Destination'],
                'services'   => [
                    ['name' => 'Cari & Booking Tiket Pesawat',  'price' => 50000,   'unit' => 'per transaksi'],
                    ['name' => 'Booking Hotel Terbaik',         'price' => 50000,   'unit' => 'per booking'],
                    ['name' => 'Paket Tiket + Hotel',           'price' => 75000,   'unit' => 'per perjalanan'],
                    ['name' => 'Pengurusan Visa Schengen',      'price' => 500000,  'unit' => 'per orang'],
                    ['name' => 'Pengurusan Visa Asia',          'price' => 200000,  'unit' => 'per orang'],
                ],
            ],
            [
                'user'    => ['nama' => 'Andre Wijaya',     'email' => 'andre.motor@gmail.com',   'telepon' => '081122200007'],
                'profile' => [
                    'specialty'        => 'Sewa Motor & Antar Jemput Wisata',
                    'bio'              => 'Sewa motor untuk wisatawan di Bali. Unit terawat, SIM tidak diperlukan untuk sewa (ada pilihan dengan atau tanpa driver). Siap antar jemput ke bandara, hotel, dan destinasi wisata.',
                    'location'         => 'Bali',
                    'experience_years' => 5,
                    'response_time'    => '< 1 jam',
                    'completed_jobs'   => 892,
                    'rating'           => 4.76,
                    'status'           => 'available',
                ],
                'categories' => ['sewa_motor', 'driver'],
                'tags'       => ['Unit Terawat', 'Harga Terjangkau', 'Antar Jemput', 'Fleksibel'],
                'services'   => [
                    ['name' => 'Sewa Motor Matic (1 hari)',      'price' => 80000,   'unit' => 'per hari'],
                    ['name' => 'Sewa Motor Matic (per minggu)',  'price' => 450000,  'unit' => 'per 7 hari'],
                    ['name' => 'Sewa Motor + Driver (8 jam)',    'price' => 250000,  'unit' => 'per hari'],
                    ['name' => 'Antar Jemput Bandara Ngurah Rai','price' => 120000,  'unit' => 'per trip'],
                ],
            ],
        ];

        foreach ($workers as $data) {
            $user = User::create([
                ...$data['user'],
                'password'  => $password,
                'is_worker' => true,
            ]);

            $worker = WorkerProfile::create([
                ...$data['profile'],
                'user_id' => $user->id,
            ]);

            $worker->categories()->sync($data['categories']);

            foreach ($data['tags'] as $tag) {
                $worker->tags()->create(['tag' => $tag]);
            }

            foreach ($data['services'] as $svc) {
                $worker->services()->create($svc);
            }
        }
    }
}
