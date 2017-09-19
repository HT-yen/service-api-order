<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('users')->insert([
            'full_name' => 'H',
            'email' => 'admin'.'@gmail.com',
            'password' => bcrypt('1234'),
            'is_admin' => 1,
        ]);

        DB::table('users')->insert([
            'full_name' => 'T',
            'email' => 'user'.'@gmail.com',
            'password' => bcrypt('1234'),
            'is_admin' => 0,
        ]);

        factory(App\User::class, 20)->create();
    }
}
