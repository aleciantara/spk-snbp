<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KuotaSnbpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $kuotas = [
            ['peminatan' => 'MIPA', 'kuota' => 60],
            ['peminatan' => 'IPS', 'kuota' => 25],
        ];

        foreach ($kuotas as $kuota) {
            DB::table('kuota_snbps')->insert($kuota);
        }
    }
}
