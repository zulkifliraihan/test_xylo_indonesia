<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payments = [
            'Gopay',
            'OVO',
            'Dana',
            'E-Money',
            'Cash'
        ];

        foreach ($payments as $pay) {
            $to = PaymentMethod::firstOrCreate(['name' => $pay]);
        }
    }
}
