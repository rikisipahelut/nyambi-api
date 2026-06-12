<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $workers = [
            [
                'user'    => ['nama' => 'Budi Santoso',     'email' => 'budi.listrik@gmail.com',   'telepon' => '081111111001'],
                'profile' => [
                    'specialty' => 'Tukang Listrik & AC', 'location' => 'Jakarta Selatan',
                    'bio' => 'Teknisi listrik berpengalaman 8 tahun. Melayani instalasi baru, perbaikan konsleting, dan servis AC semua merek.',
                    'experience_years' => 8, 'response_time' => '< 1 jam', 'completed_jobs' => 312, 'rating' => 4.90,
                    'status' => 'available',
                ],
                'categories' => ['listrik', 'ac'],
                'tags'       => ['Fast Response', 'Berpengalaman', 'Tersertifikasi', 'Bergaransi'],
                'services'   => [
                    ['name' => 'Instalasi Listrik Baru',  'price' => 250000, 'unit' => 'per titik'],
                    ['name' => 'Perbaikan Konsleting',    'price' => 150000, 'unit' => 'per kunjungan'],
                    ['name' => 'Servis & Pasang AC',      'price' => 175000, 'unit' => 'per unit'],
                    ['name' => 'Isi Freon AC',            'price' => 100000, 'unit' => 'per unit'],
                ],
            ],
            [
                'user'    => ['nama' => 'Ahmad Fauzi',      'email' => 'ahmad.kebun@gmail.com',    'telepon' => '081111111002'],
                'profile' => [
                    'specialty' => 'Tukang Kebun & Taman', 'location' => 'Depok',
                    'bio' => 'Berpengalaman 5 tahun menangani taman residensial dan komersial. Ahli dalam penataan taman modern dan perawatan rutin.',
                    'experience_years' => 5, 'response_time' => '< 2 jam', 'completed_jobs' => 187, 'rating' => 4.75,
                    'status' => 'available',
                ],
                'categories' => ['kebun', 'taman'],
                'tags'       => ['Terpercaya', 'Harga Terjangkau', 'Ramah'],
                'services'   => [
                    ['name' => 'Potong Rumput',      'price' => 150000, 'unit' => 'per kunjungan'],
                    ['name' => 'Perawatan Taman',    'price' => 300000, 'unit' => 'per bulan'],
                    ['name' => 'Tanam & Desain Taman','price' => 500000, 'unit' => 'per sesi'],
                ],
            ],
            [
                'user'    => ['nama' => 'Rudi Hartono',     'email' => 'rudi.ledeng@gmail.com',    'telepon' => '081111111003'],
                'profile' => [
                    'specialty' => 'Tukang Ledeng & Pompa Air', 'location' => 'Jakarta Barat',
                    'bio' => 'Spesialis instalasi pipa, perbaikan kebocoran, dan servis pompa air submersible. Pengalaman 10 tahun.',
                    'experience_years' => 10, 'response_time' => '< 2 jam', 'completed_jobs' => 421, 'rating' => 4.85,
                    'status' => 'available',
                ],
                'categories' => ['plumber', 'pompa_air'],
                'tags'       => ['Fast Response', 'Profesional', 'On Time', 'Bergaransi'],
                'services'   => [
                    ['name' => 'Perbaikan Kebocoran Pipa', 'price' => 120000, 'unit' => 'per titik'],
                    ['name' => 'Pasang Kran & Shower',     'price' => 80000,  'unit' => 'per buah'],
                    ['name' => 'Servis Pompa Air',         'price' => 150000, 'unit' => 'per unit'],
                    ['name' => 'Instalasi Pompa Baru',     'price' => 350000, 'unit' => 'per unit'],
                ],
            ],
            [
                'user'    => ['nama' => 'Indra Permana',    'email' => 'indra.cleaning@gmail.com', 'telepon' => '081111111004'],
                'profile' => [
                    'specialty' => 'Cleaning Service Profesional', 'location' => 'Jakarta Pusat',
                    'bio' => 'Tim cleaning service profesional untuk rumah, apartemen, dan kantor. Menggunakan peralatan dan produk ramah lingkungan.',
                    'experience_years' => 6, 'response_time' => '< 3 jam', 'completed_jobs' => 256, 'rating' => 4.80,
                    'status' => 'available',
                ],
                'categories' => ['cleaning'],
                'tags'       => ['Berpengalaman', 'Profesional', 'Ramah', 'On Time'],
                'services'   => [
                    ['name' => 'Cleaning Rumah Standard',  'price' => 200000, 'unit' => 'per kunjungan'],
                    ['name' => 'Cleaning Deep Clean',      'price' => 450000, 'unit' => 'per sesi'],
                    ['name' => 'Cleaning Apartemen',       'price' => 250000, 'unit' => 'per unit'],
                    ['name' => 'Cuci Sofa & Karpet',      'price' => 150000, 'unit' => 'per item'],
                ],
            ],
            [
                'user'    => ['nama' => 'Dedi Kurniawan',   'email' => 'dedi.bangunan@gmail.com',  'telepon' => '081111111005'],
                'profile' => [
                    'specialty' => 'Tukang Bangunan & Renovasi', 'location' => 'Bekasi',
                    'bio' => 'Kontraktor renovasi berpengalaman. Melayani renovasi total, pemasangan keramik, plesteran, dan pengecatan.',
                    'experience_years' => 12, 'response_time' => '< 4 jam', 'completed_jobs' => 98, 'rating' => 4.70,
                    'status' => 'available',
                ],
                'categories' => ['bangunan', 'keramik', 'atap'],
                'tags'       => ['Berpengalaman', 'Terpercaya', 'Harga Terjangkau'],
                'services'   => [
                    ['name' => 'Pasang Keramik',      'price' => 85000,  'unit' => 'per m2'],
                    ['name' => 'Plesteran Dinding',   'price' => 60000,  'unit' => 'per m2'],
                    ['name' => 'Perbaikan Atap Bocor','price' => 250000, 'unit' => 'per kunjungan'],
                    ['name' => 'Pasang Rangka Atap',  'price' => 120000, 'unit' => 'per m2'],
                ],
            ],
            [
                'user'    => ['nama' => 'Heri Susanto',     'email' => 'heri.cat@gmail.com',       'telepon' => '081111111006'],
                'profile' => [
                    'specialty' => 'Tukang Cat Interior & Eksterior', 'location' => 'Tangerang',
                    'bio' => 'Jasa pengecatan rumah, gedung, dan besi. Rapi, bersih, dan menggunakan cat berkualitas. Pengalaman 7 tahun.',
                    'experience_years' => 7, 'response_time' => '< 3 jam', 'completed_jobs' => 143, 'rating' => 4.65,
                    'status' => 'available',
                ],
                'categories' => ['cat'],
                'tags'       => ['Rapi', 'Berpengalaman', 'Harga Terjangkau', 'On Time'],
                'services'   => [
                    ['name' => 'Cat Interior (per m2)', 'price' => 35000,  'unit' => 'per m2'],
                    ['name' => 'Cat Eksterior (per m2)','price' => 45000,  'unit' => 'per m2'],
                    ['name' => 'Cat Pagar Besi',        'price' => 50000,  'unit' => 'per m'],
                ],
            ],
            [
                'user'    => ['nama' => 'Wahyu Nugroho',    'email' => 'wahyu.kayu@gmail.com',     'telepon' => '081111111007'],
                'profile' => [
                    'specialty' => 'Tukang Kayu & Furniture', 'location' => 'Bogor',
                    'bio' => 'Ahli pembuatan dan perbaikan furniture kayu custom. Melayani lemari, meja, kursi, dan kusen pintu/jendela.',
                    'experience_years' => 9, 'response_time' => '< 4 jam', 'completed_jobs' => 76, 'rating' => 4.88,
                    'status' => 'available',
                ],
                'categories' => ['kayu'],
                'tags'       => ['Profesional', 'Kerjaan Rapi', 'Bergaransi', 'Terpercaya'],
                'services'   => [
                    ['name' => 'Buat Lemari Custom',   'price' => 1500000, 'unit' => 'per unit'],
                    ['name' => 'Perbaikan Furniture',  'price' => 200000,  'unit' => 'per item'],
                    ['name' => 'Pasang Kusen Pintu',   'price' => 350000,  'unit' => 'per daun'],
                    ['name' => 'Pasang Plafon',        'price' => 70000,   'unit' => 'per m2'],
                ],
            ],
            [
                'user'    => ['nama' => 'Eko Prasetyo',     'email' => 'eko.las@gmail.com',        'telepon' => '081111111008'],
                'profile' => [
                    'specialty' => 'Tukang Las & Kanopi', 'location' => 'Jakarta Timur',
                    'bio' => 'Spesialis pengelasan besi, aluminium, dan stainless. Pembuatan kanopi, pagar, tangga, dan teralis.',
                    'experience_years' => 11, 'response_time' => '< 3 jam', 'completed_jobs' => 167, 'rating' => 4.72,
                    'status' => 'busy',
                ],
                'categories' => ['las'],
                'tags'       => ['Profesional', 'Berpengalaman', 'Harga Terjangkau'],
                'services'   => [
                    ['name' => 'Buat Kanopi Baja Ringan', 'price' => 180000, 'unit' => 'per m2'],
                    ['name' => 'Buat Pagar Besi',         'price' => 350000, 'unit' => 'per m'],
                    ['name' => 'Las Perbaikan',           'price' => 100000, 'unit' => 'per titik'],
                ],
            ],
            [
                'user'    => ['nama' => 'Siti Rahmawati',   'email' => 'siti.babysitter@gmail.com','telepon' => '081111111009'],
                'profile' => [
                    'specialty' => 'Baby Sitter & Perawat Lansia', 'location' => 'Jakarta Selatan',
                    'bio' => 'Berpengalaman merawat bayi dan balita sejak 2015. Sabar, telaten, dan sudah memiliki sertifikat perawatan anak dari PMI.',
                    'experience_years' => 8, 'response_time' => '< 2 jam', 'completed_jobs' => 89, 'rating' => 4.95,
                    'status' => 'available',
                ],
                'categories' => ['baby_sitter', 'caregiver'],
                'tags'       => ['Tersertifikasi', 'Terpercaya', 'Sabar', 'Berpengalaman'],
                'services'   => [
                    ['name' => 'Jaga Bayi (harian)',    'price' => 150000, 'unit' => 'per hari'],
                    ['name' => 'Jaga Bayi (bulanan)',   'price' => 3000000,'unit' => 'per bulan'],
                    ['name' => 'Rawat Lansia (harian)', 'price' => 200000, 'unit' => 'per hari'],
                ],
            ],
            [
                'user'    => ['nama' => 'Maya Kusuma',      'email' => 'maya.laundry@gmail.com',   'telepon' => '081111111010'],
                'profile' => [
                    'specialty' => 'Laundry & Setrika Kilat', 'location' => 'Bandung',
                    'bio' => 'Jasa laundry antar-jemput dengan pengerjaan rapi dan wangi. Bisa request tanpa pemutih dan pelembut tertentu.',
                    'experience_years' => 4, 'response_time' => '< 3 jam', 'completed_jobs' => 512, 'rating' => 4.60,
                    'status' => 'available',
                ],
                'categories' => ['laundry'],
                'tags'       => ['Antar Jemput', 'Cepat', 'Wangi', 'Harga Terjangkau'],
                'services'   => [
                    ['name' => 'Cuci + Setrika (reguler)', 'price' => 7000,   'unit' => 'per kg'],
                    ['name' => 'Cuci + Setrika (express)', 'price' => 12000,  'unit' => 'per kg'],
                    ['name' => 'Setrika saja',             'price' => 5000,   'unit' => 'per kg'],
                    ['name' => 'Cuci Sepatu',              'price' => 30000,  'unit' => 'per pasang'],
                ],
            ],
            [
                'user'    => ['nama' => 'Rizal Hidayat',    'email' => 'rizal.it@gmail.com',       'telepon' => '081111111011'],
                'profile' => [
                    'specialty' => 'Teknisi IT & Komputer', 'location' => 'Jakarta Pusat',
                    'bio' => 'Menangani instalasi jaringan, perbaikan laptop/PC, instalasi software, dan keamanan data. Pengalaman 6 tahun di bidang IT.',
                    'experience_years' => 6, 'response_time' => '< 1 jam', 'completed_jobs' => 234, 'rating' => 4.82,
                    'status' => 'available',
                ],
                'categories' => ['it', 'elektronik'],
                'tags'       => ['Fast Response', 'Tersertifikasi', 'Profesional', 'Terpercaya'],
                'services'   => [
                    ['name' => 'Servis Laptop/PC',       'price' => 150000, 'unit' => 'per unit'],
                    ['name' => 'Instalasi Jaringan LAN', 'price' => 200000, 'unit' => 'per kunjungan'],
                    ['name' => 'Setting WiFi & Router',  'price' => 100000, 'unit' => 'per kunjungan'],
                    ['name' => 'Install OS & Software',  'price' => 120000, 'unit' => 'per unit'],
                ],
            ],
            [
                'user'    => ['nama' => 'Fajar Wibowo',     'email' => 'fajar.foto@gmail.com',     'telepon' => '081111111012'],
                'profile' => [
                    'specialty' => 'Fotografer & Videografer', 'location' => 'Jakarta Selatan',
                    'bio' => 'Fotografer profesional untuk pernikahan, wisuda, produk, dan event korporat. Hasil editing premium dalam 3 hari kerja.',
                    'experience_years' => 5, 'response_time' => '< 4 jam', 'completed_jobs' => 118, 'rating' => 4.93,
                    'status' => 'available',
                ],
                'categories' => ['foto_video'],
                'tags'       => ['Profesional', 'Hasil Premium', 'On Time', 'Fast Editing'],
                'services'   => [
                    ['name' => 'Foto Produk',         'price' => 500000,  'unit' => 'per sesi (2 jam)'],
                    ['name' => 'Foto Wisuda',         'price' => 750000,  'unit' => 'per sesi'],
                    ['name' => 'Foto & Video Event',  'price' => 1500000, 'unit' => 'per hari'],
                    ['name' => 'Video Promosi',       'price' => 2000000, 'unit' => 'per video'],
                ],
            ],
            [
                'user'    => ['nama' => 'Dewi Anggraini',   'email' => 'dewi.makeup@gmail.com',    'telepon' => '081111111013'],
                'profile' => [
                    'specialty' => 'Makeup Artist & Salon', 'location' => 'Jakarta Barat',
                    'bio' => 'MUA profesional untuk pernikahan, wisuda, photo session, dan acara formal. Menggunakan produk premium yang aman.',
                    'experience_years' => 7, 'response_time' => '< 2 jam', 'completed_jobs' => 203, 'rating' => 4.91,
                    'status' => 'available',
                ],
                'categories' => ['makeup', 'salon'],
                'tags'       => ['Profesional', 'Produk Premium', 'Berpengalaman', 'Home Service'],
                'services'   => [
                    ['name' => 'Makeup Wisuda',        'price' => 350000, 'unit' => 'per sesi'],
                    ['name' => 'Makeup Pernikahan',    'price' => 800000, 'unit' => 'per sesi'],
                    ['name' => 'Makeup Natural',       'price' => 200000, 'unit' => 'per sesi'],
                    ['name' => 'Creambath & Hair Spa', 'price' => 150000, 'unit' => 'per sesi'],
                ],
            ],
            [
                'user'    => ['nama' => 'Agus Salim',       'email' => 'agus.driver@gmail.com',    'telepon' => '081111111014'],
                'profile' => [
                    'specialty' => 'Supir Pribadi Profesional', 'location' => 'Surabaya',
                    'bio' => 'Supir pribadi berpengalaman dengan SIM A & B. Menguasai area Surabaya dan sekitarnya. Sopan, tepat waktu, dan jaga kebersihan mobil.',
                    'experience_years' => 9, 'response_time' => '< 1 jam', 'completed_jobs' => 445, 'rating' => 4.78,
                    'status' => 'available',
                ],
                'categories' => ['driver'],
                'tags'       => ['On Time', 'Sopan', 'Terpercaya', 'SIM Lengkap'],
                'services'   => [
                    ['name' => 'Antar Jemput (dalam kota)', 'price' => 150000, 'unit' => 'per hari'],
                    ['name' => 'Sewa Supir Full Day',       'price' => 350000, 'unit' => 'per hari'],
                    ['name' => 'Antar Bandara',             'price' => 200000, 'unit' => 'per trip'],
                ],
            ],
            [
                'user'    => ['nama' => 'Tri Wahyono',      'email' => 'tri.kunci@gmail.com',      'telepon' => '081111111015'],
                'profile' => [
                    'specialty' => 'Tukang Kunci & Duplikat', 'location' => 'Depok',
                    'bio' => 'Spesialis buka pintu terkunci, duplikat kunci, dan ganti kunci. Melayani 24 jam untuk keadaan darurat.',
                    'experience_years' => 8, 'response_time' => '< 30 menit', 'completed_jobs' => 678, 'rating' => 4.86,
                    'status' => 'available',
                ],
                'categories' => ['tukang_kunci'],
                'tags'       => ['24 Jam', 'Fast Response', 'Harga Terjangkau', 'Terpercaya'],
                'services'   => [
                    ['name' => 'Buka Pintu Terkunci',   'price' => 150000, 'unit' => 'per kunjungan'],
                    ['name' => 'Duplikat Kunci',        'price' => 25000,  'unit' => 'per buah'],
                    ['name' => 'Ganti Kunci Pintu',     'price' => 100000, 'unit' => 'per unit'],
                    ['name' => 'Pasang Kunci Digital',  'price' => 250000, 'unit' => 'per unit'],
                ],
            ],
            [
                'user'    => ['nama' => 'Nita Sari',        'email' => 'nita.catering@gmail.com',  'telepon' => '081111111016'],
                'profile' => [
                    'specialty' => 'Jasa Catering & Memasak', 'location' => 'Jakarta Timur',
                    'bio' => 'Menyediakan catering untuk arisan, ulang tahun, pernikahan, dan makan siang kantor. Menu masakan Indonesia dan internasional.',
                    'experience_years' => 6, 'response_time' => '< 4 jam', 'completed_jobs' => 134, 'rating' => 4.83,
                    'status' => 'available',
                ],
                'categories' => ['catering', 'masak'],
                'tags'       => ['Halal', 'Harga Terjangkau', 'Higienis', 'Berpengalaman'],
                'services'   => [
                    ['name' => 'Catering Nasi Box',      'price' => 25000,  'unit' => 'per box (min 20)'],
                    ['name' => 'Masak di Rumah (harian)','price' => 200000, 'unit' => 'per hari'],
                    ['name' => 'Catering Prasmanan',     'price' => 55000,  'unit' => 'per pax (min 50)'],
                ],
            ],
            [
                'user'    => ['nama' => 'Bambang Setiawan', 'email' => 'bambang.pompa@gmail.com',  'telepon' => '081111111017'],
                'profile' => [
                    'specialty' => 'Servis Pompa Air & Listrik', 'location' => 'Jakarta Utara',
                    'bio' => 'Teknisi pompa air dan instalasi listrik daya. Berpengalaman menangani pompa submersible, pompa jetpump, dan panel listrik.',
                    'experience_years' => 13, 'response_time' => '< 2 jam', 'completed_jobs' => 389, 'rating' => 4.76,
                    'status' => 'available',
                ],
                'categories' => ['pompa_air', 'listrik'],
                'tags'       => ['Berpengalaman', 'Bergaransi', 'Profesional', 'On Time'],
                'services'   => [
                    ['name' => 'Servis Pompa Jetpump',     'price' => 180000, 'unit' => 'per kunjungan'],
                    ['name' => 'Servis Pompa Submersible', 'price' => 250000, 'unit' => 'per kunjungan'],
                    ['name' => 'Instalasi Panel Listrik',  'price' => 400000, 'unit' => 'per panel'],
                ],
            ],
            [
                'user'    => ['nama' => 'Rina Marlina',     'email' => 'rina.fitness@gmail.com',   'telepon' => '081111111018'],
                'profile' => [
                    'specialty' => 'Personal Trainer & Zumba', 'location' => 'Surabaya',
                    'bio' => 'Certified personal trainer dengan spesialisasi weight loss, muscle building, dan Zumba. Bisa home visit atau di gym klien.',
                    'experience_years' => 5, 'response_time' => '< 2 jam', 'completed_jobs' => 67, 'rating' => 4.97,
                    'status' => 'available',
                ],
                'categories' => ['fitness'],
                'tags'       => ['Tersertifikasi', 'Profesional', 'Motivatif', 'Berpengalaman'],
                'services'   => [
                    ['name' => 'Personal Training (1 sesi)', 'price' => 200000, 'unit' => 'per sesi (1 jam)'],
                    ['name' => 'Paket 10 Sesi',             'price' => 1800000,'unit' => 'per paket'],
                    ['name' => 'Kelas Zumba Privat',        'price' => 150000, 'unit' => 'per sesi'],
                ],
            ],
            [
                'user'    => ['nama' => 'Doni Saputra',     'email' => 'doni.wedding@gmail.com',   'telepon' => '081111111019'],
                'profile' => [
                    'specialty' => 'Wedding Organizer & Dekorasi', 'location' => 'Bandung',
                    'bio' => 'WO profesional dengan pengalaman menangani 200+ pernikahan. Melayani dekorasi, koordinasi vendor, dan rundown acara.',
                    'experience_years' => 8, 'response_time' => '< 6 jam', 'completed_jobs' => 48, 'rating' => 4.94,
                    'status' => 'available',
                ],
                'categories' => ['wedding', 'dekorasi'],
                'tags'       => ['Profesional', 'Kreatif', 'Terpercaya', 'Full Service'],
                'services'   => [
                    ['name' => 'WO Full Service',       'price' => 15000000,'unit' => 'per acara'],
                    ['name' => 'Dekorasi Gedung',       'price' => 8000000, 'unit' => 'per acara'],
                    ['name' => 'Dekorasi Akad Nikah',   'price' => 3000000, 'unit' => 'per acara'],
                    ['name' => 'Koordinator Hari H',    'price' => 2500000, 'unit' => 'per acara'],
                ],
            ],
            [
                'user'    => ['nama' => 'Joko Purnomo',     'email' => 'joko.pest@gmail.com',      'telepon' => '081111111020'],
                'profile' => [
                    'specialty' => 'Pest Control & Fumigasi', 'location' => 'Yogyakarta',
                    'bio' => 'Jasa pembasmian hama profesional: rayap, kecoa, tikus, nyamuk, dan lalat. Menggunakan bahan kimia tersertifikasi Kementan.',
                    'experience_years' => 7, 'response_time' => '< 3 jam', 'completed_jobs' => 211, 'rating' => 4.79,
                    'status' => 'available',
                ],
                'categories' => ['pest_control'],
                'tags'       => ['Tersertifikasi', 'Aman', 'Bergaransi', 'Profesional'],
                'services'   => [
                    ['name' => 'Basmi Rayap',        'price' => 500000,  'unit' => 'per kunjungan'],
                    ['name' => 'Basmi Kecoa & Tikus','price' => 300000,  'unit' => 'per kunjungan'],
                    ['name' => 'Fogging Nyamuk',     'price' => 250000,  'unit' => 'per kunjungan'],
                    ['name' => 'Fumigasi Gudang',    'price' => 1500000, 'unit' => 'per sesi'],
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
