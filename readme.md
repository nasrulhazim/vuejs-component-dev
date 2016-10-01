# VueJS Component Development

**There's two section in VueJs Component Development, the first one is API Development and the second section is VueJs Component Development**

## API Development

Create a model + migration - `php artisan make:model Task -m`.

Open up the `Task` model and update as following:

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

Open up the `Task` model migration script - `<timestamp>_create_tasks_table.php` and update as following:

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

Next, you need to add a relation from users to tasks - `a user has many tasks`.  Open up `User.php` model and update as following:

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

Now you're done with Task model and it's migration script. Next, let's setup the Task factory and seeder file. Open up the `ModelFactory.php` and update as following:

```php
$factory->define(App\Task::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'status' => $faker->randomElement(['New','In Progress','Cancel','Done'])
    ];
});
```

Then create a `TaskSeeder` file - `php artisan make:seeder TaskSeeder` - and open it up and add the following in the `run` method:

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

Now open up `DatabaseSeeder.php` and call the `TaskSeeder` class:

```php
$this->call(TaskSeeder::class);
```

Run `php artisan db:seed` and you will have 10 users and 1000 tasks.

Next, we need an API endpoint - `http://domain.com/api/tasks` - to retrieve tasks for current logged in user. Open up `routes/api.php` and add the following to the route file.

```php
Route::get('/tasks/{id}', function($id){
	$tasks = \App\Task::where('user_id', $id)->orderBy('created_at','asc')->paginate(5);
	return response()->json($tasks);
})->middleware('auth:api');
```

p/s: You want to create controller for your API, in my case, I just want to simplify the tutorial. :)

# VueJs Component Development

In VueJs Component, it consist of 3 components - template, style and script - below is the skeleton that you can use.

```html
<template>
    <!-- your html here -->
</template>

<style type="text/css">
    
</style>

<script type="text/javascript">
    export default {
        ready() {
            console.log('Component ready.')
        }
    }
</script>
```

For our example, please use the following - a component to fetch task list based on logged in user.

```html
<template>
    <ul>
      <li v-for="task in response.data">
        {{ task.title }}
      </li>
    </ul>
</template>

<style type="text/css">
    
</style>

<script type="text/javascript">
    export default {
        ready() {
            console.log('Component ready.');
            this.fetch();
        },
        data : function() {
            return {
                response : {}
            }
        },
        methods : {
            fetch : function() {
                this.$http.get('/api/tasks/'+Laravel.userId).then((response) => {
                    console.log(response.data);
                    this.response = response.data;
                }, (response) => {
                    // handle error here
                    alert(response.data.error);
                });
            }
        }
    }
</script>
```

In your `layouts/app.blade.php`, add the following to pass in user's id to JavaScript - which required in API call.

```php
<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
        'userId' => Auth::user()->id
    ]); ?>
</script>
```

After you have define the VueJs Component, you need to register your VueJs Component in `resources/assets/js/app.js`.

```javacript
Vue.component('tasks', require('./components/Tasks.vue'));
```

Then just run `gulp` to compile.

Next, open up `home.blade.php` and add the VueJs Component to your view

```html
<tasks></tasks>
```

Now you may login to your application - you should see the list being populated nicely. 

Congratulation, you're done with basic VueJs Component Development.