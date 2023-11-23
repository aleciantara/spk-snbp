<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotKriteriasSeeder extends Seeder
{
    public function run()
    {
        $criteria = [
            ['nama_kriteria' => 'Rapor', 'bobot' => 0.5],
            ['nama_kriteria' => 'Prestasi', 'bobot' => 0.3],
            ['nama_kriteria' => 'Sikap', 'bobot' => 0.2],
        ];

        foreach ($criteria as $criterion) {
            DB::table('bobot_kriterias')->insert($criterion);
        }
    }
}
