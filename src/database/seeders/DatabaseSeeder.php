<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $season = $this->setSeasons($faker);
        $brands = $this->setBrands($faker);
        $cars = $this->setCars($faker, $brands);
        $prices = $this->setPrices($faker, $cars, $season);

        DB::table('roles')->insert([
            [
                'name' => 'Super Admin',
                'guard_name' => 'web'
            ], [
                'name' => 'Client',
                'guard_name' => 'web'
            ], [
                'name' => 'User',
                'guard_name' => 'web'
            ]
        ]);

        DB::table('seasons')->insert($season);
        DB::table('brands')->insert($brands);
        DB::table('cars')->insert($cars);
        DB::table('car_price')->insert($prices);
    }

    /**
     * @param \Faker\Generator $faker
     * @return array[]
     */
    private function setSeasons(\Faker\Generator $faker): array
    {
        return [
            [
                'id' => $faker->uuid,
                'name' => 'Peak',
                'start' => '06/01',
                'end' => '09/15'
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Mid',
                'start' => null,
                'end' => null
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Off',
                'start' => '11/01',
                'end' => '05/01'
            ],
        ];
    }

    /**
     * @param \Faker\Generator $faker
     * @return array[]
     */
    private function setBrands(\Faker\Generator $faker): array
    {
        return [
            [
                'id' => $faker->uuid,
                'name' => 'Seat',
                'model' => 'León',
                'stock' => 3
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Seat',
                'model' => 'Ibiza',
                'stock' => 5
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Nissan',
                'model' => 'Qashqai',
                'stock' => 2
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Jaguar',
                'model' => 'e-pace',
                'stock' => 1
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Mercedes',
                'model' => 'Vito',
                'stock' => 2
            ],
        ];
    }

    /**
     * @param \Faker\Generator $faker
     * @param array            $brands
     * @return array[]
     */
    private function setCars(\Faker\Generator $faker, array $brands): array
    {
        return [
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[0]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[0]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[0]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[1]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[1]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[1]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[1]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[1]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[2]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[2]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[3]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[4]['id'],
                'status' => 0
            ],
            [
                'id' => $faker->uuid,
                'brand_id' => $brands[4]['id'],
                'status' => 0
            ],
        ];
    }

    /**
     * @param \Faker\Generator $faker
     * @param array            $cars
     * @param array            $season
     * @return array[]
     */
    private function setPrices(\Faker\Generator $faker, array $cars, array $season): array
    {
        return [
            // Precios Seat León
            [
                'id' => $faker->uuid,
                'car_id' => $cars[0]['id'],
                'season_id' => $season[0]['id'],
                'price' => 98.43
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[0]['id'],
                'season_id' => $season[1]['id'],
                'price' => 76.89
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[0]['id'],
                'season_id' => $season[2]['id'],
                'price' => 53.65
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[1]['id'],
                'season_id' => $season[0]['id'],
                'price' => 98.43
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[1]['id'],
                'season_id' => $season[1]['id'],
                'price' => 76.89
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[1]['id'],
                'season_id' => $season[2]['id'],
                'price' => 53.65
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[2]['id'],
                'season_id' => $season[0]['id'],
                'price' => 76.89
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[2]['id'],
                'season_id' => $season[1]['id'],
                'price' => 53.65
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[2]['id'],
                'season_id' => $season[2]['id'],
                'price' => 53.65
            ],


            // Precios Seat Ibiza
            [
                'id' => $faker->uuid,
                'car_id' => $cars[3]['id'],
                'season_id' => $season[0]['id'],
                'price' => 85.12
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[3]['id'],
                'season_id' => $season[1]['id'],
                'price' => 65.73
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[3]['id'],
                'season_id' => $season[2]['id'],
                'price' => 46.85
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[4]['id'],
                'season_id' => $season[0]['id'],
                'price' => 85.12
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[4]['id'],
                'season_id' => $season[1]['id'],
                'price' => 65.73
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[4]['id'],
                'season_id' => $season[2]['id'],
                'price' => 46.85
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[5]['id'],
                'season_id' => $season[0]['id'],
                'price' => 85.12
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[5]['id'],
                'season_id' => $season[1]['id'],
                'price' => 65.73
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[5]['id'],
                'season_id' => $season[2]['id'],
                'price' => 46.85
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[6]['id'],
                'season_id' => $season[0]['id'],
                'price' => 85.12
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[6]['id'],
                'season_id' => $season[1]['id'],
                'price' => 65.73
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[6]['id'],
                'season_id' => $season[2]['id'],
                'price' => 46.85
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[7]['id'],
                'season_id' => $season[0]['id'],
                'price' => 85.12
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[7]['id'],
                'season_id' => $season[1]['id'],
                'price' => 65.73
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[7]['id'],
                'season_id' => $season[2]['id'],
                'price' => 46.85
            ],


            // Precios Nissan Qashqai
            [
                'id' => $faker->uuid,
                'car_id' => $cars[8]['id'],
                'season_id' => $season[0]['id'],
                'price' => 101.46
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[8]['id'],
                'season_id' => $season[1]['id'],
                'price' => 82.94
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[8]['id'],
                'season_id' => $season[2]['id'],
                'price' => 59.87
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[9]['id'],
                'season_id' => $season[0]['id'],
                'price' => 101.46
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[9]['id'],
                'season_id' => $season[1]['id'],
                'price' => 82.94
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[9]['id'],
                'season_id' => $season[2]['id'],
                'price' => 59.87
            ],


            // Precios Jaguar e-pace
            [
                'id' => $faker->uuid,
                'car_id' => $cars[10]['id'],
                'season_id' => $season[0]['id'],
                'price' => 120.54
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[10]['id'],
                'season_id' => $season[1]['id'],
                'price' => 91.35
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[10]['id'],
                'season_id' => $season[2]['id'],
                'price' => 70.27
            ],


            // Precios Mercedes Vito
            [
                'id' => $faker->uuid,
                'car_id' => $cars[11]['id'],
                'season_id' => $season[0]['id'],
                'price' => 109.16
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[11]['id'],
                'season_id' => $season[1]['id'],
                'price' => 89.64
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[11]['id'],
                'season_id' => $season[2]['id'],
                'price' => 64.97
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[12]['id'],
                'season_id' => $season[0]['id'],
                'price' => 109.16
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[12]['id'],
                'season_id' => $season[1]['id'],
                'price' => 89.64
            ],
            [
                'id' => $faker->uuid,
                'car_id' => $cars[12]['id'],
                'season_id' => $season[2]['id'],
                'price' => 64.97
            ],
        ];
    }
}
