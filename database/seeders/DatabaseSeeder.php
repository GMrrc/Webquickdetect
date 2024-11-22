<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = 'Admin';
        $admin->surname = 'User';
        $admin->email = 'admin@gmail.com';
        $admin->password = Hash::make('Admin!1234');
        $admin->verify_email = true;
        $admin->dateOfBirth = '1990-01-01';
        $admin->role = 'admin';
        $admin->save();
    }
}
