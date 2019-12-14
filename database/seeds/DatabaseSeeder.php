<?php

use App\Plan\Plan;
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
        $this->call(CommunesDistrictsTableSeeder::class);

        for ($i = 0; $i < 20; ++$i) {
            $user = factory(User::class)->create([
                'username' => 'user'.$i,
                'email' => 'user'.$i.'@gmail.com',
                'password' => bcrypt('secret'),
                'commune_id' => rand(1, 24),
                'district_id' => rand(1, 50),
            ]);
            $user->attachRole('member');
        }
        $user = factory(User::class)->create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
            'commune_id' => rand(1, 24),
            'district_id' => rand(1, 50),
        ]);
        $user->attachRole('admin');

        $this->call(DemandTableSeeder::class);

        $plans = [
         [
             'name' => 'Basic',
             'slug' => 'basic',
             'amount' => 5,
             'stripe_id' => 'prod_FuqVdb5jsxrav8',
         ],
         [
             'name' => 'Professionnel',
             'slug' => 'professionnel',
             'amount' => 10,
             'stripe_id' => 'prod_FuqXZQSMMMxoKR',
         ],
         [
             'name' => 'Premium',
             'slug' => 'premium',
             'amount' => 15,
             'stripe_id' => 'prod_FuqXr5hpsKj2Nf',
         ],
        ];

        foreach ($plans as $plan) {
            factory(Plan::class)->create([
                'name' => $plan['name'],
                'slug' => $plan['slug'],
                'amount' => $plan['amount'],
                'stripe_id' => $plan['stripe_id'],
            ]);
        }

        //$contract = $demand->contractCandidature($candidatures);
    }
}
