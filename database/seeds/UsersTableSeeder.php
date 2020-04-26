<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        // Apply role
        $user->addRole('Super Admin');

        $user->name = Str::random(10);
        $user->email = "webmaster@fusedsoftware.com";
        $user->password = Hash::make('1234qwer');
        $user->save();


        $emails = [
            'drew@fusedsoftware.com',
            'james@fusedsoftware.com',
            'john@fusedsoftware.com',
            'cres@fusedsoftware.com'
        ];

        foreach ($emails as $email) {
            $user = new User();
            $user->addRole('Super Admin');
            $user->name = 'Admin';
            $user->email = $email;
            $user->password = Hash::make('Passwd345-6');
            $user->save();
        }
    }
}
