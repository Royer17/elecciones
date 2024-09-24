<?php

use Illuminate\Database\Seeder;
use sisVentas\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Tramite',
        ]);
    }
}
