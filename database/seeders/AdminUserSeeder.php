<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['nip' => '199903262025041004'],
            [
                'name' => 'Racmawan Sidik Laminullah',
                'email' => 'rlaminullah@gmail.com',
                'role' => 'admin',
            ]
        );
    }
}
