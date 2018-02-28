<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*roles de usuario*/
        DB::table('type')->insert([
            'name' => 'Administrador',
            'group_type_id' => 1,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('type')->insert([
            'name' => 'Cliente',
            'group_type_id' => 1,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        /*prioridad de tickets*/
        DB::table('type')->insert([
            'name' => 'Alto',
            'group_type_id' => 2,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('type')->insert([
            'name' => 'Medio',
            'group_type_id' => 2,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('type')->insert([
            'name' => 'Bajo',
            'group_type_id' => 2,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        /*estados de tickets*/
        DB::table('type')->insert([
            'name' => 'Abierto',
            'group_type_id' => 3,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('type')->insert([
            'name' => 'Recibido',
            'group_type_id' => 3,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('type')->insert([
            'name' => 'En proceso',
            'group_type_id' => 3,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        
        DB::table('type')->insert([
            'name' => 'Rechazado',
            'group_type_id' => 3,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        DB::table('type')->insert([
            'name' => 'Cerrado',
            'group_type_id' => 3,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
       
        
        
    }
}