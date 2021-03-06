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
            'phone' => '0976 876 554',
            'password' => bcrypt('123456')
        ]);

        DB::table('users')->insert([
            'full_name' => 'T',
            'email' => 'user'.'@gmail.com',
            'phone' => '0976 876 764',
            'password' => bcrypt('123456')
        ]);

        factory(App\User::class, 20)->create();
    }
}
