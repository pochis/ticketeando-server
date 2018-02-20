<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([
            'name' => 'Andres',
            'lastname' => 'Arbelaez Acevedo',
            'email' => 'sheva852@hotmail.com',
            'password' => app('hash')->make(1234),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }
}