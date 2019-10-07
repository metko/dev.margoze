<?php

use App\Sector\Sector;
use App\Commune\Commune;
use App\District\District;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CommunesDistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $file = file_get_contents('communes.json', true);
        $file = json_decode($file);

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
    }
}
