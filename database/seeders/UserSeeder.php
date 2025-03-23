<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => '$2y$10$jfEi4DFU9zHQ26hpebjyxO5/.MY9weqP9cSWPuns626aDWWNYtsIC',
            'ativo' => 1,
            'created_at' => '2025-03-04 15:17:50',
            'updated_at' => '2025-03-04 15:17:50'
        ]);
    }
} 