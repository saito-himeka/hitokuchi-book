<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $genres = ['小説', '暮らし', '絵本', 'ビジネス・教養','人文・思想', 'エッセイ', '政治・社会', 'アート・デザイン'];

        foreach ($genres as $name) {
            Genre::create(['name' => $name]);
        }
    }
}
