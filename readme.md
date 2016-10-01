# VueJS Component Development

**There's two section in VueJs Component Development, the first one is API Development and the second section is VueJs Component Development**

## API Development

1. Create a model + migration - `php artisan make:model Task -m`
2. Open up the `Task` model and update as following:

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    	'user_id',
    	'name',
    	'description',
    	'status'
    ];

    public function owner()
    {
    	return $this->belongsTo('App\User');
    }
}
```

3. Open up the `Task` model migration script - `<timestamp>_create_tasks_table.php` and update as following:

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->string('title');
            $table->text('description');
            $table->enum('status',['New','In Progress','Cancel','Done']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
```

4. Next, you need to add a relation from users to tasks - `a user has many tasks`.  Open up `User.php` model and update as following:

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tasks() {
        return $this->hasMany('App\Task');
    }
}
```

5. Now you're done with Task model and it's migration script. Next, let's setup the Task factory and seeder file. Open up the `ModelFactory.php` and update as following:

```php
$factory->define(App\Task::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'status' => $faker->randomElement(['New','In Progress','Cancel','Done'])
    ];
});
```

6. Then create a `TaskSeeder` file - `php artisan make:seeder TaskSeeder` - and open it up and add the following in the `run` method:

```php
\App\User::truncate();
\App\Task::truncate();
// create 10 users
factory(\App\User::class, 10)->create()->each(function($u){
	// each user have 100 tasks
	factory(\App\Task::class, 100)->create()->each(function($t) use ($u) {
		$t->user_id = $u->id;
		$t->save();
	});
});
```

7. Now open up `DatabaseSeeder.php` and call the `TaskSeeder` class:

```php
$this->call(TaskSeeder::class);
```

8. Run `php artisan db:seed` and you will have 10 users and 1000 tasks.

9. Next, we need an API endpoint - `http://domain.com/api/tasks` - to retrieve tasks for current logged in user. Open up `routes/api.php` and add the following to the route file.

```php
Route::get('/tasks', function(){
	$tasks = \App\Task::where('user_id', Auth::user()->id)->orderBy('created_at','asc')->get();
	return response()->json($tasks);
})->middleware('auth:api');
```