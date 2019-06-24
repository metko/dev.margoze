<?php

use App\Demand\Demand;
use Illuminate\Support\Str;
use App\Demand\DemandSector;
use App\Demand\DemandStatus;
use App\Demand\DemandCategory;
use Illuminate\Database\Seeder;

class DemandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $status = ['default', 'urgent', 'cancelled', 'contracted'];
        $category = ['Jardinage', 'Informatique', 'Soutien scolaire'];
        $sector = ['Nord', 'Nord-Est', 'Est', 'Sud-Est', 'Sud', 'Sud-Ouest', 'Ouest', 'Nord-Ouest'];

        foreach ($status as $s) {
            DemandStatus::create(['name' => $s, 'slug' => Str::slug($s)]);
        }
        foreach ($category as $c) {
            DemandCategory::create(['name' => $c, 'slug' => Str::slug($c)]);
        }
        foreach ($sector as $s) {
            DemandSector::create(['name' => $s, 'slug' => Str::slug($s)]);
        }
        for ($i = 0; $i < 10; ++$i) {
            factory(Demand::class)->create([
                'owner_id' => rand(1, 2),
                'sector_id' => rand(1, 8),
                'category_id' => rand(1, 3),
            ]);
        }
    }
}
