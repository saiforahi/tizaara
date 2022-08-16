<?php

use Illuminate\Database\Seeder;

class PackagePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i = 1; $i < 100; $i ++) {
            \App\PackagePayment::query()->create([
                'user_id' => 3,
                'membership_plan_id' => 1,
                'payment_date' => $faker->dateTime,
                'payment_type' => 1,
                'amount' => $faker->randomNumber(3),
                'status' => 1,
            ]);
        }
    }
}
