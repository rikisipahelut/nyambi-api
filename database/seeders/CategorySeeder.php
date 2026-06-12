<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 'kebun',           'icon' => 'grass',                'title' => 'Tukang Kebun'],
            ['id' => 'listrik',         'icon' => 'bolt',                 'title' => 'Tukang Listrik'],
            ['id' => 'cleaning',        'icon' => 'cleaning_services',    'title' => 'Cleaning Service'],
            ['id' => 'bangunan',        'icon' => 'construction',         'title' => 'Tukang Bangunan'],
            ['id' => 'ac',              'icon' => 'ac_unit',              'title' => 'Servis AC'],
            ['id' => 'cat',             'icon' => 'format_paint',         'title' => 'Tukang Cat'],
            ['id' => 'plumber',         'icon' => 'plumbing',             'title' => 'Tukang Ledeng'],
            ['id' => 'kayu',            'icon' => 'carpenter',            'title' => 'Tukang Kayu'],
            ['id' => 'las',             'icon' => 'hardware',             'title' => 'Tukang Las'],
            ['id' => 'elektronik',      'icon' => 'electrical_services',  'title' => 'Teknisi Elektronik'],
            ['id' => 'kurir',           'icon' => 'local_shipping',       'title' => 'Jasa Kurir'],
            ['id' => 'laundry',         'icon' => 'local_laundry_service','title' => 'Laundry & Setrika'],
            ['id' => 'masak',           'icon' => 'restaurant',           'title' => 'Jasa Memasak'],
            ['id' => 'baby_sitter',     'icon' => 'child_care',           'title' => 'Baby Sitter'],
            ['id' => 'caregiver',       'icon' => 'elderly',              'title' => 'Perawat Lansia'],
            ['id' => 'driver',          'icon' => 'directions_car',       'title' => 'Supir Pribadi'],
            ['id' => 'security',        'icon' => 'security',             'title' => 'Satpam / Security'],
            ['id' => 'tukang_kunci',    'icon' => 'key',                  'title' => 'Tukang Kunci'],
            ['id' => 'pest_control',    'icon' => 'pest_control',         'title' => 'Pest Control'],
            ['id' => 'pompa_air',       'icon' => 'water_pump',           'title' => 'Servis Pompa Air'],
            ['id' => 'atap',            'icon' => 'roofing',              'title' => 'Tukang Atap'],
            ['id' => 'keramik',         'icon' => 'grid_view',            'title' => 'Tukang Keramik'],
            ['id' => 'kaca',            'icon' => 'window',               'title' => 'Tukang Kaca'],
            ['id' => 'pindahan',        'icon' => 'move_item',            'title' => 'Jasa Pindahan'],
            ['id' => 'foto_video',      'icon' => 'camera_alt',           'title' => 'Fotografer & Videografer'],
            ['id' => 'makeup',          'icon' => 'brush',                'title' => 'Makeup Artist'],
            ['id' => 'salon',           'icon' => 'content_cut',          'title' => 'Salon Kecantikan'],
            ['id' => 'pijat',           'icon' => 'spa',                  'title' => 'Jasa Pijat'],
            ['id' => 'fitness',         'icon' => 'fitness_center',       'title' => 'Personal Trainer'],
            ['id' => 'les_privat',      'icon' => 'school',               'title' => 'Les Privat'],
            ['id' => 'translator',      'icon' => 'translate',            'title' => 'Penerjemah'],
            ['id' => 'design',          'icon' => 'design_services',      'title' => 'Jasa Desain'],
            ['id' => 'it',              'icon' => 'computer',             'title' => 'Teknisi IT & Komputer'],
            ['id' => 'percetakan',      'icon' => 'print',                'title' => 'Jasa Cetak & Print'],
            ['id' => 'jahit',           'icon' => 'checkroom',            'title' => 'Penjahit'],
            ['id' => 'taman',           'icon' => 'park',                 'title' => 'Desain Taman'],
            ['id' => 'aquarium',        'icon' => 'water',                'title' => 'Perawatan Aquarium'],
            ['id' => 'hewan',           'icon' => 'pets',                 'title' => 'Perawatan Hewan Peliharaan'],
            ['id' => 'akuntan',         'icon' => 'calculate',            'title' => 'Jasa Akuntansi'],
            ['id' => 'notaris',         'icon' => 'gavel',                'title' => 'Jasa Notaris'],
            ['id' => 'hukum',           'icon' => 'balance',              'title' => 'Konsultasi Hukum'],
            ['id' => 'psikolog',        'icon' => 'psychology',           'title' => 'Konseling & Psikologi'],
            ['id' => 'gizi',            'icon' => 'nutrition',            'title' => 'Konsultasi Gizi'],
            ['id' => 'dokter_gigi',     'icon' => 'medical_services',     'title' => 'Dokter Gigi'],
            ['id' => 'fisioterapi',     'icon' => 'healing',              'title' => 'Fisioterapi'],
            ['id' => 'dekorasi',        'icon' => 'celebration',          'title' => 'Dekorasi Acara'],
            ['id' => 'catering',        'icon' => 'restaurant_menu',      'title' => 'Jasa Catering'],
            ['id' => 'wedding',         'icon' => 'favorite',             'title' => 'Wedding Organizer'],
            ['id' => 'entertainment',   'icon' => 'music_note',           'title' => 'Hiburan & Entertainer'],
        ];

        $now = now();
        DB::table('categories')->insert(
            array_map(fn($c) => [...$c, 'created_at' => $now], $categories)
        );
    }
}
