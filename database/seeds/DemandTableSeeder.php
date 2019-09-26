<?php

use App\User\User;
use App\Demand\Demand;
use App\Sector\Sector;
use App\Commune\Commune;
use App\Category\Category;
use App\District\District;
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
        $file = file_get_contents('communes.json', true);
        $file = json_decode($file);

        $faker = Faker\Factory::create();

        $category = ['Bricolage', 'Jardinage', 'Electricité', 'Plomberie', 'Photographie', 'Animaux', 'Cours', 'Dj', 'Service à la personne', 'Coursier', 'Coach', 'Traiteur', 'Piercing', 'Tatoo', 'Astrologie', 'Cuisine', 'Réparation'];

        foreach ($category as $c) {
            Category::create(['name' => $c, 'slug' => Str::slug($c)]);
        }

        foreach ($file as $s => $communes) {
            $sector = Sector::create(['name' => $s, 'slug' => Str::slug($s)]);

            foreach ($communes as $c => $district) {
                $commune = factory(Commune::class)->create([
                        'name' => $c,
                        'slug' => Str::slug($c),
                        'sector_id' => $sector->id,
                ]);

                foreach ($district as $d) {
                    $district = factory(District::class)->create([
                        'name' => $d,
                        'slug' => Str::slug($d),
                        'sector_id' => $sector->id,
                        'commune_id' => $commune->id,
                ]);
                }
            }
        }

        for ($i = 0; $i < 20; ++$i) {
            $demand = factory(Demand::class)->create([
                'owner_id' => rand(1, 10),
                'sector_id' => rand(1, 8),
                'commune_id' => rand(1, 12),
                'district_id' => rand(1, 8),
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
