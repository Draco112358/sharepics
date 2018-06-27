<?php

use Illuminate\Database\Seeder;

class SeedAlbumTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       factory(App\Models\Album::class, 10)->create()->each(function ($album){
           $cats = \App\Models\Category::inRandomOrder()->take(2)->pluck('id');
           $cats->each(function ($cat_id)use($album){
                \App\Models\AlbumsCategory::create([
                    'album_id' => $album->id,
                    'category_id' => $cat_id
                ]);
           });
       });
    }
}
