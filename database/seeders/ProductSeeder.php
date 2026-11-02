<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      

        for($j = 1; $j <=8; $j++){
            for($i = 1; $i <=10; $i++){


              $gallery = [];

                for($k = 0; $k < rand(0, 7); $k++){

                    $gallery[] = 'assets/img/products/' . ($k + 1) . '.jpg';   
                }

              shuffle($gallery);
                DB::table('products')->insert([
                'title' => $title = fake()->unique()->sentence(3),
                'slug' => str()->slug($title, '-'),
                'category_id' => $j,
                'excerpt' => fake()->paragraph(2),
                'content' => fake()->paragraph(3, true),
                'image' => 'assets/img/products/' . rand(1,8) . '.jpg',
                'gallery' => $gallery ? json_encode($gallery) : null,
                'price' => $price = rand(50,1000),
                'old_price' => fake()->randomElement([0,intval($price * 1.1)]),
                // 'old_price' => rand(0,1) ? intval($price * 1.1) : 0
                'is_hit' => rand(0,1),
                'is_new' => rand(0,1),
                'created_at' => now(), 
                'updated_at' => now()
           

                ]);
            }
        }
    }
}
