<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
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
         /*\App\Models\Permission::factory(10)->create();
         \App\Models\Role::factory(10)->create();

         $this->call(RolesAndPermissionsSeed::class);*/
         //Thread::factory(100)->create();
         Answer::factory(100)->create();
         /*Channel::factory(100)->create();
         User::factory(100)->create();*/
    }
}
