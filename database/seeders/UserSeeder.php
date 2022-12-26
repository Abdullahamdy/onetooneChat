<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            User::create(['name'=>'Abdullah','email'=>'abdullah@gmail.com','image'=>'1.jpg','password'=>bcrypt(123456)]);
            User::create(['name'=>'Hbdullah','email'=>'Hamdy@gmail.com','image'=>'2.jpg','password'=>bcrypt(123456)]);
            User::create(['name'=>'Elgayar','email'=>'Elgayar@gmail.com','image'=>'3.jpg','password'=>bcrypt(123456)]);

    }
}
