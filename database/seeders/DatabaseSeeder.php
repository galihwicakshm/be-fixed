<?php

namespace Database\Seeders;

use App\Models\AdminProfile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $adminContent = User::create([
            'role' => 1,
            'email' => 'admincontent@mail.test',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
        ]);

        AdminProfile::create([
            'user_id' => $adminContent->id,
            'first_name' => 'Admin',
            'last_name' => 'Konten',
        ]);
        
        $adminProposal = User::create([
            'role' => 2,
            'email' => 'adminproposal@mail.test',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
        ]);

        AdminProfile::create([
            'user_id' => $adminProposal->id,
            'first_name' => 'Admin',
            'last_name' => 'Pengajuan Usulan',
        ]);
        
        $adminSuper = User::create([
            'role' => 3,
            'email' => 'adminsuper@mail.test',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
        ]);

        AdminProfile::create([
            'user_id' => $adminSuper->id,
            'first_name' => 'Admin',
            'last_name' => 'Super',
        ]);

        // Make user in UserProfiles table
        $user = User::create([
            'role' => 5,
            'email' => 'asdjasd@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'User',
            'last_name' => 'Test',
            'phone_number' => '081234567890',
            'college' => 'Universitas Indonesia',
        ]);
    }
}
