<?php

use App\User\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'username' => 'Admin2',
            'email' => 'admin2@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        $this->call(MetkontrolTableSeeder::class);
        User::whereusername('Admin')->first()->attachRole('Admin');
        User::whereusername('Admin2')->first()->attachRole('Admin');
    }
}
