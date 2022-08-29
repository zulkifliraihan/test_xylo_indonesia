<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staff = User::firstOrCreate([
            'name' => 'Petugas Parkir',
            'email' => 'staff@xylo.co.id',
            'password' => Hash::make('123123')
        ]);
        $staff->assignRole('staff');

        $superadmin = User::firstOrCreate([
            'name' => 'Administrator',
            'email' => 'admin@xylo.co.id',
            'password' => Hash::make('123123')
        ]);
        $superadmin->assignRole('admin');

    }
}
