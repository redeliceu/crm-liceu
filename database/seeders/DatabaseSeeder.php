<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Template;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        // Criar a escola primeiro
        $school = School::firstOrCreate(['name' => 'Colégio Itaqua']);

        Template::create([
            'school_id' => $school->id,
            'name' => 'lembete_v10',
            'number' => '1723393465022698',
        ]);

        Template::create([
            'school_id' => $school->id,
            'name' => 'colegio_itaqua_mensalidade_em_aberto_20250808',
            'number' => '2168545506964928',
        ]);



    }
}
