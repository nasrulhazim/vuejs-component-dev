<?php

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::truncate();
        \App\Task::truncate();
        factory(\App\User::class, 10)->create()->each(function($u){
        	factory(\App\Task::class, 100)->create()->each(function($t) use ($u) {
        		$t->user_id = $u->id;
        		$t->save();
        	});
        });
    }
}
