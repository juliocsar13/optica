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
        DB::table('users')->insert([
            'id' => '1',
            'usuario' => 'admin',
            'password' => bcrypt('123456'),
            'idrol' => '1',
            'idsucursal' => '1'
        ]);
    }
}
