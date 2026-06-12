<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['nama' => 'Andi Saputra',      'email' => 'andi@gmail.com',     'telepon' => '081234567890'],
            ['nama' => 'Siti Nurhaliza',     'email' => 'siti@gmail.com',     'telepon' => '082345678901'],
            ['nama' => 'Brama Kusuma',       'email' => 'brama@gmail.com',    'telepon' => '083456789012'],
            ['nama' => 'Ratna Dewi',         'email' => 'ratna@gmail.com',    'telepon' => '085567890123'],
            ['nama' => 'Faisal Hakim',       'email' => 'faisal@gmail.com',   'telepon' => '087678901234'],
            ['nama' => 'Lina Marlina',       'email' => 'lina@gmail.com',     'telepon' => '088789012345'],
            ['nama' => 'Doni Hermawan',      'email' => 'doni@gmail.com',     'telepon' => '089890123456'],
            ['nama' => 'Putri Anggraeni',    'email' => 'putri@gmail.com',    'telepon' => '081901234567'],
            ['nama' => 'Hendra Gunawan',     'email' => 'hendra@gmail.com',   'telepon' => '082012345678'],
            ['nama' => 'Mega Wulandari',     'email' => 'mega@gmail.com',     'telepon' => '083123456789'],
        ];

        $password = Hash::make('password');

        foreach ($customers as $data) {
            User::create([...$data, 'password' => $password, 'is_worker' => false]);
        }
    }
}
