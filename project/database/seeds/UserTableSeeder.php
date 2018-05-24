<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        User::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'username' => $faker->username,
                'password' => $faker->password,
                'phone_number' => $faker->phone_number,
                'avatar' => $faker->avatar,
                'birthday' => $faker->birthday,
                'address' => $faker->address,
                'bio' => $faker->bio,
            ]);
        }
    }
}
