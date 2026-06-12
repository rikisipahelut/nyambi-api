<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('is_worker', false)->pluck('id')->toArray();
        $workers   = WorkerProfile::with('user')->get();

        $descriptions = [
            'Perbaikan instalasi listrik di kamar mandi lantai 2, ada kabel yang terkelupas.',
            'Potong rumput halaman depan dan belakang, sudah lebat sekali.',
            'Kebocoran pipa di bawah wastafel dapur, air merembes ke lantai.',
            'Cleaning apartemen 2BR setelah selesai kontrak, butuh deep cleaning.',
            'Pasang keramik kamar mandi ukuran 40x40, sekitar 8m2.',
            'Cat ulang 3 kamar tidur, warna putih semua.',
            'Perbaikan pompa air yang tidak bisa naik ke tangki atas.',
            'Servis AC 1,5 PK di ruang tamu, sudah tidak dingin.',
            'Buat kanopi baja ringan untuk garasi, ukuran 3x4 meter.',
            'Pest control untuk rumah 2 lantai, ada rayap di kusen pintu.',
            'Jaga bayi usia 6 bulan, seharian penuh karena ada acara.',
            'Antar jemput ke Bandara Soekarno-Hatta besok pagi pukul 05.00.',
            'Personal training 1 jam, fokus pada core dan cardio.',
            'Makeup wisuda untuk 2 orang, acara pagi hari.',
            'Catering nasi box untuk arisan 30 orang, menu ayam bakar.',
            'Install ulang Windows 11 dan software-software kerja.',
            'Dekorasi ulang tahun anak di rumah, tema unicorn.',
            'Cuci sofa 3 dudukan dan 2 kursi single.',
            'Perbaikan kusen pintu kayu yang sudah lapuk.',
            'Foto produk untuk 20 item baju online shop.',
            'Isi freon AC 2 PK di ruang keluarga.',
            'Laundry kilat baju pesta, butuh selesai 1 hari.',
            'Ganti kunci pintu utama yang sudah rusak.',
            'Masak makan siang untuk keluarga 5 orang selama seminggu.',
            'Pasang teralis jendela 3 lubang, bahan besi hollow.',
        ];

        $addresses = [
            'Jl. Kemang Raya No.45, Jakarta Selatan 12730',
            'Jl. Melati Putih Blok B5 No.12, Depok 16414',
            'Jl. Raya Bekasi KM 18 No.7, Bekasi 17131',
            'Apartemen Thamrin Residence Tower A Lt.12 Unit 12C, Jakarta Pusat',
            'Jl. Cempaka Baru III No.22, Jakarta Pusat 10640',
            'Komplek Bumi Serpong Damai Sektor 7 Blok G3 No.9, Tangerang 15322',
            'Jl. Raya Bogor KM 31 No.15, Bogor 16953',
            'Jl. Pemuda No.88, Surabaya 60271',
            'Jl. Sudirman No.155, Bandung 40113',
            'Jl. Malioboro No.56, Yogyakarta 55213',
        ];

        $statuses = ['selesai', 'selesai', 'selesai', 'selesai', 'selesai', 'selesai', 'selesai', 'selesai',
                     'dikonfirmasi', 'dikonfirmasi', 'dikonfirmasi', 'dikonfirmasi',
                     'menunggu', 'menunggu', 'menunggu', 'menunggu', 'menunggu',
                     'dibatalkan', 'dibatalkan'];

        $baseDate = now()->subDays(60);

        foreach (range(1, 40) as $i) {
            $worker   = $workers->random();
            $customer = $customers[array_rand($customers)];
            $status   = $statuses[array_rand($statuses)];
            $daysAgo  = rand(1, 55);

            Order::create([
                'user_id'   => $customer,
                'worker_id' => $worker->id,
                'tanggal'   => $baseDate->copy()->addDays($daysAgo)->toDateString(),
                'waktu'     => ['08:00', '09:00', '10:00', '13:00', '14:00', '15:00'][rand(0, 5)],
                'deskripsi' => $descriptions[array_rand($descriptions)],
                'alamat'    => $addresses[array_rand($addresses)],
                'telepon'   => '08' . rand(10000000, 99999999) . rand(10, 99),
                'status'    => $status,
                'created_at'=> $baseDate->copy()->addDays($daysAgo)->subDays(rand(1, 3)),
                'updated_at'=> $baseDate->copy()->addDays($daysAgo)->subDays(rand(0, 1)),
            ]);
        }
    }
}
