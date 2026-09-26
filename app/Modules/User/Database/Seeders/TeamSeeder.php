<?php
namespace App\Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\User\Entities\User;
use App\Modules\User\Entities\UserType;
use Illuminate\Support\Facades\Hash;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersData = [
            [
                'full_name' => 'زهرا حاسبی',
                'avatar'=>null,
                'mobile'=>'098642783979',
                'email' => 'user1@example.com',
                'password' => bcrypt('password1')
            ],
            [
                'full_name' => 'عباس غفوری',
                'avatar'=>null,
                'mobile'=>'098642783979',
                'email' => 'user2@example.com',
                'password' => bcrypt('password2')
            ],
            [
                'full_name' => 'اکبر موسوی',
                'avatar'=>null,
                'mobile'=>'098642783979',
                'email' => 'user3@example.com',
                'password' =>bcrypt('password3')
            ],
            [
                'full_name' => 'سبحان نطری',
                'avatar'=>null,
                'mobile'=>'098642783979',
                'email' => 'user4@example.com',
                'password' => bcrypt('password4')
            ]
        ];

        foreach ($usersData as $userData) {
            $user = \App\Modules\User\Entities\User::create($userData);

            UserType::create([
                'user_id' => $user->id,
                'type' => 'teacher'
            ]);
        }
    }
}
