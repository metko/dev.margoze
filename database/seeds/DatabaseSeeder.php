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
            'username' => 'User1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'username' => 'User2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'username' => 'Admin1',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'username' => 'Admin2',
            'email' => 'admin2@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'username' => 'rita',
            'email' => 'rita@rita.com',
            'password' => bcrypt('secret'),
        ]);

        $this->call(MetkontrolTableSeeder::class);

        User::whereusername('User1')->first()->attachRole('member');
        User::whereusername('User2')->first()->attachRole('member');
        User::whereusername('Admin1')->first()->attachRole('Admin');
        User::whereusername('Admin2')->first()->attachRole('Admin');

        $this->call(DemandTableSeeder::class);
    }
}
