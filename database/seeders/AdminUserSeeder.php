<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', null);
        $password = env('ADMIN_PASSWORD', null);

        if (!$email || !$password) {
            throw new \Exception(__('messages.admin_env_missing'));
        }
        
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => __('messages.admin_name'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );
    }
}
