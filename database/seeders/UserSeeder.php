<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Crear 10 usuarios trabajadores con Faker
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => "usuario{$i}@test.com",
                'telefono' => $faker->phoneNumber,
                'password' => Hash::make('test1234'), // Contraseña predeterminada
                'role' => 'trabajador',
            ]);
        }
    }
}
