<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $approves = ['meleancaionut1@gmail.com', 'meleanca.adrian.ionut@gmail.com', 'adrian.meleanca@mediadigi.ro', 'daniel@mediadigi.ro', 'daniel.stanica@mediadigi.ro', 'iulia.rosca@mediadigi.ro'];
        foreach ($approves as $key => $approve)
        {
            DB::table('approves')->insert([
                'email' => $approve
            ]);
        }
    }
}
