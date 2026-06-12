<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [];
        $now  = now();

        // Notifikasi sistem untuk semua user
        foreach (User::all() as $user) {
            $rows[] = [
                'id'         => \Illuminate\Support\Str::uuid()->toString(),
                'user_id'    => $user->id,
                'icon'       => 'celebration',
                'title'      => 'Selamat datang di Nyambi!',
                'body'       => 'Temukan ratusan pekerja terampil di sekitar Anda. Mulai pesan sekarang!',
                'href'       => '/explore',
                'is_read'    => true,
                'created_at' => $now->copy()->subDays(30),
            ];
        }

        // Notifikasi berdasarkan order
        $orders = Order::with(['user', 'worker.user'])->whereNotNull('user_id')->get();

        foreach ($orders as $order) {
            if (!$order->user_id || !$order->worker_id) continue;

            switch ($order->status) {
                case 'dikonfirmasi':
                    $rows[] = [
                        'id'         => \Illuminate\Support\Str::uuid()->toString(),
                        'user_id'    => $order->user_id,
                        'icon'       => 'check_circle',
                        'title'      => 'Pesanan Dikonfirmasi',
                        'body'       => "Pekerja {$order->worker?->user?->nama} telah mengkonfirmasi pesanan Anda untuk tanggal {$order->tanggal}.",
                        'href'       => "/orders/{$order->id}",
                        'is_read'    => rand(0, 1) === 1,
                        'created_at' => $now->copy()->subDays(rand(1, 20)),
                    ];
                    // Notif untuk pekerja: ada pesanan baru
                    if ($workerUser = $order->worker?->user) {
                        $rows[] = [
                            'id'         => \Illuminate\Support\Str::uuid()->toString(),
                            'user_id'    => $workerUser->id,
                            'icon'       => 'assignment',
                            'title'      => 'Pesanan Baru Masuk',
                            'body'       => "Anda mendapatkan pesanan baru dari pelanggan untuk tanggal {$order->tanggal}.",
                            'href'       => "/orders/{$order->id}",
                            'is_read'    => rand(0, 1) === 1,
                            'created_at' => $now->copy()->subDays(rand(1, 20)),
                        ];
                    }
                    break;

                case 'selesai':
                    $rows[] = [
                        'id'         => \Illuminate\Support\Str::uuid()->toString(),
                        'user_id'    => $order->user_id,
                        'icon'       => 'star',
                        'title'      => 'Berikan Ulasan Anda',
                        'body'       => "Bagaimana pengalaman Anda dengan {$order->worker?->user?->nama}? Berikan ulasan untuk membantu pekerja lain.",
                        'href'       => "/orders/{$order->id}/review",
                        'is_read'    => rand(0, 1) === 1,
                        'created_at' => $now->copy()->subDays(rand(1, 15)),
                    ];
                    if ($workerUser = $order->worker?->user) {
                        $rows[] = [
                            'id'         => \Illuminate\Support\Str::uuid()->toString(),
                            'user_id'    => $workerUser->id,
                            'icon'       => 'payments',
                            'title'      => 'Pesanan Selesai',
                            'body'       => 'Pesanan telah ditandai selesai. Pendapatan Anda sudah dapat dicairkan.',
                            'href'       => "/orders/{$order->id}",
                            'is_read'    => rand(0, 1) === 1,
                            'created_at' => $now->copy()->subDays(rand(1, 15)),
                        ];
                    }
                    break;

                case 'dibatalkan':
                    $rows[] = [
                        'id'         => \Illuminate\Support\Str::uuid()->toString(),
                        'user_id'    => $order->user_id,
                        'icon'       => 'cancel',
                        'title'      => 'Pesanan Dibatalkan',
                        'body'       => "Pesanan Anda untuk tanggal {$order->tanggal} telah dibatalkan.",
                        'href'       => "/orders",
                        'is_read'    => true,
                        'created_at' => $now->copy()->subDays(rand(5, 25)),
                    ];
                    break;
            }
        }

        // Notifikasi promo untuk beberapa user
        $promoUsers = User::where('is_worker', false)->limit(5)->pluck('id');
        foreach ($promoUsers as $userId) {
            $rows[] = [
                'id'         => \Illuminate\Support\Str::uuid()->toString(),
                'user_id'    => $userId,
                'icon'       => 'local_offer',
                'title'      => 'Promo Spesial Akhir Bulan!',
                'body'       => 'Dapatkan diskon 20% untuk pemesanan pertama Anda minggu ini. Gunakan kode: NYAMBI20',
                'href'       => '/promo',
                'is_read'    => false,
                'created_at' => $now->copy()->subDays(2),
            ];
        }

        // Insert dalam batch untuk efisiensi
        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('notifications')->insert($chunk);
        }
    }
}
