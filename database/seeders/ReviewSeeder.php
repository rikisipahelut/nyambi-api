<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Review;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $positiveComments = [
            'Pekerjaannya sangat rapi dan bersih, tepat waktu juga. Sangat puas!',
            'Hasilnya memuaskan, sesuai ekspektasi. Harga juga terjangkau.',
            'Pak/Bu-nya ramah dan profesional. Akan panggil lagi kalau butuh.',
            'Cepat tanggap, kerjaan beres dalam waktu singkat. Recommended!',
            'Hasil kerja memuaskan, komunikasi lancar, dan tepat waktu. 5 bintang!',
            'Sangat profesional dan berpengalaman. Tidak mengecewakan sama sekali.',
            'Barang/pekerjaan beres sempurna, bahkan melebihi ekspektasi. Terima kasih!',
            'Sangat puas dengan hasilnya. Harga sesuai dengan kualitas.',
            'Kerja cepat dan bersih. Tidak meninggalkan kotoran apapun. Bagus!',
            'Komunikatif sebelum dan saat pengerjaan. Hasil akhirnya memuaskan.',
            'Sudah panggil beliau berkali-kali, selalu puas dengan hasilnya.',
            'On time dan langsung tahu masalahnya. Solusi diberikan dengan cepat.',
        ];

        $goodComments = [
            'Hasilnya bagus, ada sedikit hal kecil yang kurang tapi sudah diperbaiki.',
            'Cukup puas, pengerjaan selesai tepat waktu walau agak lama.',
            'Oke lah, kerjaan beres sesuai yang diminta. Harga standar.',
            'Bagus, hanya perlu sedikit penyesuaian di akhir tapi overall puas.',
            'Hasilnya sesuai, komunikasi cukup responsif. Akan coba lagi.',
        ];

        $completedOrders = Order::where('status', 'selesai')
            ->whereDoesntHave('review')
            ->get();

        foreach ($completedOrders as $order) {
            $isHighRating = rand(1, 10) <= 8;
            $rating       = $isHighRating ? rand(4, 5) : 3;
            $comment      = $isHighRating
                ? $positiveComments[array_rand($positiveComments)]
                : $goodComments[array_rand($goodComments)];

            if (!$order->user_id || !$order->worker_id) {
                continue;
            }

            Review::create([
                'order_id'   => $order->id,
                'user_id'    => $order->user_id,
                'worker_id'  => $order->worker_id,
                'rating'     => $rating,
                'comment'    => $comment,
                'created_at' => $order->updated_at->addDays(rand(1, 3)),
            ]);
        }

        // Recalculate all worker ratings
        WorkerProfile::all()->each(function ($worker) {
            $avg = $worker->reviews()->avg('rating');
            if ($avg !== null) {
                $worker->update([
                    'rating'         => round($avg, 2),
                    'completed_jobs' => $worker->reviews()->count() + rand(10, 50),
                ]);
            }
        });
    }
}
