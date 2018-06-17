<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class SeedCategoryTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = [
            'city',
            'food',
            'animals',
            'abstract',
            'nightlife',
            'fashion',
            'nature',
            'sports',
            'technics'
        ];

        foreach ($cats as $cat){
            Category::create(
                ['name' => $cat]
            );
        }
    }
}
