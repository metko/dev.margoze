<?php

use App\User\User;
use App\Demand\Demand;
use App\Sector\Sector;
use App\Category\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Candidature\Candidature;

class DemandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $category = ['Jardinage', 'Informatique', 'Soutien scolaire'];
        $sector = ['Nord', 'Nord-Est', 'Est', 'Sud-Est', 'Sud', 'Sud-Ouest', 'Ouest', 'Nord-Ouest'];

        foreach ($category as $c) {
            Category::create(['name' => $c, 'slug' => Str::slug($c)]);
        }
        foreach ($sector as $s) {
            Sector::create(['name' => $s, 'slug' => Str::slug($s)]);
        }
        for ($i = 0; $i < 20; ++$i) {
            $demand = factory(Demand::class)->create([
                'owner_id' => rand(1, 10),
                'sector_id' => rand(1, 8),
                'category_id' => rand(1, 3),
                'created_at' => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now', $timezone = null),
                'valid_until' => $faker->dateTimeBetween($startDate = 'now', $endDate = '1 month', $timezone = null),
            ]);
            for ($i2 = 0; $i2 < rand(1, 10); ++$i2) {
                $candidature = factory(Candidature::class)->raw([
                    'owner_id' => rand(11, 20),
                ]);
                $rand = rand(11, 20);
                $user = User::whereId($rand)->first();
                $user->apply($demand, $candidature);
            }
            //$demand->contractCandidature($candidature);
        }
    }
}
