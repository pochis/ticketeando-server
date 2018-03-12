<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_type')->insert([
            'name' => 'Roles de usuario',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('group_type')->insert([
            'name' => 'Prioridad de ticket',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('group_type')->insert([
            'name' => 'Estados de ticket',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('group_type')->insert([
            'name' => 'Resoluciones de tickets',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }
}