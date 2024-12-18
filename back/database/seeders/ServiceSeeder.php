<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Schema::create('services', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('description');
        //     $table->decimal('price', 8, 2);
        //     $table->string('image');
        //     $table->boolean('is_active')->default(true);
        //     $table->timestamps();
        // });

        $services = [
            [
                'name' => 'Service 1',
                'description' => 'Description for Service 1',
                'price' => 100.00,
                'image' => 'service1.jpg',
            ],
            [
                'name' => 'Service 2',
                'description' => 'Description for Service 2',
                'price' => 200.00,
                'image' => 'service2.jpg',
            ],
            [
                'name' => 'Service 3',
                'description' => 'Description for Service 3',
                'price' => 300.00,
                'image' => 'service3.jpg',
            ],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
