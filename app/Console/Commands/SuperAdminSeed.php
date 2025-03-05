<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        if ($admin) {
            $this->info('Super admin user already exists.');
            return;
        }

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
        ]);

        $admin->assignRole('super_admin');

        $this->info('Admin user created successfully.');
    }
}
