<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api')->insert([
            'secret' => str_random(40),
            'domain' => 'http://ticketeando-client-pochis852.c9users.io',
            'email' => 'sheva852@hotmail.com',
            'status' => 1,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }
}