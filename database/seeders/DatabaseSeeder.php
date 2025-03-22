<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Rodando o EventSeeder para criar os 10 eventos
        $this->call([
            EventSeeder::class,
        ]);

        // Se quiser adicionar um usuário de teste, pode fazer assim:
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}