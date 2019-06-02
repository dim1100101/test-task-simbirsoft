<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Uploader;
use Faker\Generator as Faker;


/**
 * фабрика для заполнения данными БД пользователя, загружающего файл
 */
$factory->define(Uploader::class, function (Faker $faker) {
    $email = $faker->unique()->safeEmail;
    return [
        'email' => $email,
        'hash' => md5($email . microtime()),
    ];
});
