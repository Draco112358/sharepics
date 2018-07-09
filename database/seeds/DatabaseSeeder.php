<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Category;
use App\Models\AlbumCategory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        User::truncate();
        Album::truncate();
        Photo::truncate();
        Category::truncate();
        AlbumCategory::truncate();



        $this->call(SeedUserTable::class);
        $this->call(SeedCategoryTable::class);
        $this->call(SeedAlbumTable::class);

        $this->call(SeedPhotoTable::class);

    }
}
