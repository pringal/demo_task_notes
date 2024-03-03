<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\NoteAttachments;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->has(
                Task::factory()->has(
                        Note::factory()->has(
                                NoteAttachments::factory()->count(2)
                            )->count(3)
                    )->count(5)
            )->count(5)->create();
    }
}
