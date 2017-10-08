<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Redis\Secret::class, function () {
    static $ciphertext;

    $encrypt = function ($data, $password) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(App\Models\Secret::CIPHER_METHOD));
        $encrypted = openssl_encrypt($data, App\Models\Secret::CIPHER_METHOD, $password, 0, $iv);
        return $encrypted . ':' . base64_encode($iv);
    };

    return [
        'ciphertext' => $ciphertext ?: $ciphertext = $encrypt('foobar', '12345'),
        'ip' => '127.0.0.1',
        'attempts' => 0,
        'expires_at' => date('Y-m-d H:i:s', time() + 10)
    ];
});
