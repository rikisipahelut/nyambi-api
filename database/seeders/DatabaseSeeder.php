<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            TravelSeeder::class,
            CustomerSeeder::class,
            WorkerSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            FavoriteSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
