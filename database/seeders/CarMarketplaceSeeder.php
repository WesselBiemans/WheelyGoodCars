<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CarMarketplaceSeeder extends Seeder
{
    /**
     * Seed the car marketplace sample data.
     */
    public function run(): void
    {
        $faker = fake();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@wheelygoodcars.test',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone_number' => null,
        ]);

        $providers = collect([$admin])->merge(
            collect(range(1, 149))->map(function () use ($faker) {
                return User::create([
                    'name' => $faker->name(),
                    'email' => $faker->unique()->safeEmail(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'phone_number' => $faker->optional()->numerify('06########'),
                ]);
            })
        );

        $tagNames = [
            'Nieuw',
            'Occasion',
            'Elektrisch',
            'Hybride',
            'Benzine',
            'Diesel',
            'Automaat',
            'Handgeschakeld',
            'SUV',
            'Station',
            'Hatchback',
            'Sedan',
            'Cabrio',
            'Sport',
            'Luxe',
            'Zuinig',
            'Dealeronderhouden',
            'Eerste Eigenaar',
            'APK Nieuw',
            'BTW Auto',
        ];

        $tags = collect($tagNames)->map(function (string $name, int $index) {
            return Tag::create([
                'name' => $name,
                'color' => sprintf('#%06X', (($index + 1) * 723943) % 0xFFFFFF),
            ]);
        });

        $brands = ['Volkswagen', 'BMW', 'Audi', 'Toyota', 'Kia', 'Ford', 'Peugeot', 'Renault', 'Skoda', 'Tesla'];
        $models = ['Golf', '3 Serie', 'A3', 'Corolla', 'Niro', 'Focus', '208', 'Clio', 'Octavia', 'Model 3'];
        $colors = ['Zwart', 'Wit', 'Grijs', 'Blauw', 'Rood', 'Groen', 'Zilver'];

        DB::transaction(function () use ($faker, $providers, $tags, $brands, $models, $colors): void {
            for ($i = 0; $i < 250; $i++) {
                $car = Car::create([
                    'user_id' => $providers->random()->id,
                    'license_plate' => strtoupper($faker->bothify('??####')),
                    'brand' => $faker->randomElement($brands),
                    'model' => $faker->randomElement($models),
                    'price' => $faker->randomFloat(2, 2500, 95000),
                    'mileage' => $faker->numberBetween(5000, 320000),
                    'seats' => $faker->randomElement([2, 4, 5, 7]),
                    'doors' => $faker->randomElement([2, 3, 4, 5]),
                    'production_year' => $faker->numberBetween(2005, (int) date('Y')),
                    'weight' => $faker->numberBetween(850, 2600),
                    'color' => $faker->randomElement($colors),
                    'image' => null,
                    'sold_at' => $faker->boolean(15) ? $faker->dateTimeBetween('-2 years', 'now') : null,
                    'views' => $faker->numberBetween(0, 5000),
                ]);

                $car->tags()->attach(
                    $tags->random($faker->numberBetween(1, 4))->pluck('id')->all()
                );
            }
        });
    }
}
