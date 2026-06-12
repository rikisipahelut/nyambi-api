<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\User;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('is_worker', false)->pluck('id')->toArray();
        $workerIds = WorkerProfile::pluck('id')->toArray();

        $inserted = [];

        foreach ($customers as $userId) {
            $count       = rand(2, 5);
            $shuffled    = $workerIds;
            shuffle($shuffled);
            $picks = array_slice($shuffled, 0, $count);

            foreach ($picks as $workerId) {
                $key = "{$userId}|{$workerId}";
                if (isset($inserted[$key])) {
                    continue;
                }

                DB::table('favorites')->insert([
                    'user_id'    => $userId,
                    'worker_id'  => $workerId,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                $inserted[$key] = true;
            }
        }
    }
}
