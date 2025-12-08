<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartments = [
            ['name' => 'Byt A-101', 'layout' => '2-izbový', 'area' => 54.00, 'floor' => 1, 'price' => 145000.00, 'status' => 'available'],
            ['name' => 'Byt A-102', 'layout' => '3-izbový', 'area' => 72.00, 'floor' => 1, 'price' => 189000.00, 'status' => 'reserved'],
            ['name' => 'Byt A-103', 'layout' => '1-izbový', 'area' => 38.00, 'floor' => 1, 'price' => 98000.00, 'status' => 'available'],
            ['name' => 'Byt A-201', 'layout' => '2-izbový', 'area' => 56.00, 'floor' => 2, 'price' => 152000.00, 'status' => 'available'],
            ['name' => 'Byt A-202', 'layout' => '4-izbový', 'area' => 95.00, 'floor' => 2, 'price' => 265000.00, 'status' => 'sold'],
            ['name' => 'Byt A-203', 'layout' => '3-izbový', 'area' => 75.00, 'floor' => 2, 'price' => 198000.00, 'status' => 'available'],
            ['name' => 'Byt A-301', 'layout' => '2-izbový', 'area' => 58.00, 'floor' => 3, 'price' => 158000.00, 'status' => 'available'],
            ['name' => 'Byt A-302', 'layout' => '3-izbový', 'area' => 78.00, 'floor' => 3, 'price' => 205000.00, 'status' => 'reserved'],
        ];

        foreach ($apartments as $apartment) {
            Apartment::create($apartment);
        }
    }
}
