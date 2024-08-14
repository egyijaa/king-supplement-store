<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'King Supplement 99',
            'address' => 'Jl. Pangeran Natakusuma No. 99 Pontianak',
            'whatsapp' => '085652400077'
        ]);
    }
}
