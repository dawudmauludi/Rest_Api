<?php

namespace Database\Seeders;

use App\Models\Vaccines;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vaccines::insert($this->insert());
    }

    public function insert(){
        return [
            [
                'name' => 'Sinovac'
            ],
            [
                'name' => 'AstraZeneca'
            ],
            [
                'name' => 'Moderna'
            ],
            [
                'name' => 'Pfizer'
            ],
            [
                'name' => 'Sinnopharm'
            ],
        ];
    }
}
