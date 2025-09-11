<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Celia Azevedo',
            'email' => 'celia@email.com',
            'password' => Hash::make('Segredo#ortiz'),
            'avatar'=> 'https://res.cloudinary.com/dufaejhwh/image/upload/v1753755908/avatar_mulher_chanel_jhcvt5.jpg'
        ]);

        User::factory()->create([
            'name' => 'Marcos Marins',
            'email' => 'marcos@email.com',
            'password' => Hash::make('Segredo#ortiz'),
            'avatar'=> 'https://res.cloudinary.com/dufaejhwh/image/upload/v1757283886/avatar_masculino_li8fo1.jpg'
        ]);
    }
}
