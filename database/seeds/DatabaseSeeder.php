<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Uploader::class, 10000)->create()->each(function ($uploader) {
            $uploader->files()->save(factory(App\File::class)->make());
        });
    }
}
