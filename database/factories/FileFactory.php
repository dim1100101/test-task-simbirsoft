<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\File;
use Faker\Generator as Faker;


/**
 * фабрика для заполнения данными БД данными о загруженных файлах
 */
$factory->define(File::class, function (Faker $faker) {
    $fileName = md5($faker->text . microtime());
    return [
        'description' => $faker->text,
        'filename' => $fileName,
        'hash' => md5($fileName . microtime()),
    ];
});
