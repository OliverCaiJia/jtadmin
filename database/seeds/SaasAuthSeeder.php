<?php

use Illuminate\Database\Seeder;

class SaasAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Orm\SaasAuth::class, 10)->create()->each(function ($saasAuth) {
            factory(\App\Models\Orm\SaasAccount::class)->create([
                'user_id' => $saasAuth->jt_user_id
            ]);
        });
    }
}
