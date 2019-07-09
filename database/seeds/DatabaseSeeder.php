<?php

use App\User\User;
use App\Demand\Demand;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(MetkontrolTableSeeder::class);

        for ($i = 0; $i < 20; ++$i) {
            $user = factory(User::class)->create([
                'username' => 'user'.$i,
                'email' => 'user'.$i.'@gmail.com',
                'password' => bcrypt('secret'),
            ]);
            $user->attachRole('member');
        }
        $user = factory(User::class)->create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'secret',
        ]);
        $user->attachRole('admin');

        $this->call(DemandTableSeeder::class);

        //$contract = $demand->contractCandidature($candidatures);
    }
}
