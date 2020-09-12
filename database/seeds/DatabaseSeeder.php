<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        
        $faker = Faker::create();
        foreach (range(1,4) as $index) {
            DB::table('admin')->insert([
                'username' => $faker->name,
                'email' => $faker->email,
                'address' => $faker->address,
                'mobile' => $faker->numberBetween(1,13),
                'nic' => $faker->numberBetween(1,11),
                'status' => $faker->randomElement([0, 1]),
                'password' => bcrypt('secret'),
                'remember_token' => Str::random(64),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }
    }
}