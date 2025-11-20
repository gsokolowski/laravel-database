Laravel Database - DB Queries Eloquent

Based on this Udemy course 
https://www.udemy.com/course/laravel-database-queries-from-beginner-to-super-advanced/learn/lecture/20871528#overview

Project Laravel-datadabe

Git
https://github.com/gsokolowski/laravel-database


Create seeder for user
greg@mac laravel-database % php artisan make:seeder UserSeeder

   INFO  Seeder [database/seeders/UserSeeder.php] created successfully.  

greg@mac laravel-database % php artisan make:seeder UserSeeder

   INFO  Seeder [database/seeders/UserSeeder.php] created successfully.  

greg@mac laravel-database % php artisan migrate:fresh --seed

   INFO  Preparing database. 



Add debug bar to laravel project
greg@mac laravel-database % composer require barryvdh/laravel-debugbar --dev
./composer.json has been updated
Running composer update barryvdh/laravel-debugbar

Shows app env - local or prod
dd(app()->environment());


Type of Laravel queries 

// Eloquent style query

$categories = Category::select('id','title')->orderBy('title')->get();


// Builder query type

$categories = DB::table('category')
               ->selectRaw('id','title')
               ->orderBy('title')
               ->get();


// Eloquent style query

$tags = Tag::select('id', 'name')->get();


// Mixed style query, eloquent and buildrr query together

$tags = Tag::select('id', 'name')->orderByDesc(
       DB::table('post_tag')
           ->selectRaw('count(tag_id) as tag_count')
           ->whereColumn('tags.id', 'post_tag.tag_id')
           ->orderBy('tag_count', 'desc')
           ->limit(1)
)
->get();


// Eloquent style

$latest_posts = Post::select('id', 'title')
                       ->latest()
                       ->take(5)
                       ->withCount('comments')
                       ->get();




In db queries in web.php

Route::get('/', function () {

   // $pdo = DB::connection('mysql')->getPdo();
   // // Example: run a raw query using PDO
   // $stmt = $pdo->query('SELECT * FROM users WHERE id IN (1,2)');
   // $results = $stmt->fetchAll(PDO::FETCH_OBJ);
   // dump($results);

   // DB facade
   $users = DB::select('select * from users where id in (?, ?)', [1,2]);
   $users2 = DB::select('select id, email from users where id = ?', [2]);
   //dump($users2);
  

   $result = DB::select('select * from users where id = ? and name = ?', [1, 'Adalberto Gerlach']);
   $result = DB::select('select * from users where id = :id', ['id' => 1]);

   // DB::insert('insert into users (name, email,password) values (?, ?, ?)', ['Inserted Name', 'email@fdf.fd','passw']);

   // $affected = DB::update('update users set email = "updatedemail@email.com" where email = ?', ['email@fdf.fd']);

   // $deleted = DB::delete('delete from users where id = ?',[6]);

   // DB::statement('truncate table users');

   // $result = DB::select('select * from users'); // returns array
   // $result = DB::table('users')->select()->get(); // returns Collection
  
   // $result = User::all(); // returns Eloquent\collection
   // dump($result);

});


In AppServiceProvider.php
To be able to see sql queries as console you can do that
   /**
    * Bootstrap any application services.
    */
   public function boot(): void
   {
       DB::listen(function ($query) {
           // to see the sql query in the console
           // var_dump($query->sql);
           // var_dump($query->bindings);
           // var_dump($query->time);
       });
   }

DB::transactions: How to do it 

So you delete all users from table users
Then you try to update user id 4 and this should fail
So transaction should be rolled back

<?php


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {


   // get all users from users table
   // using DB facade
   $result = DB::table('users')->select()->get(); // returns Collection
   dump($result);


   DB::transaction(function () use ($result) {
       try {
           // delete all users from users table
           DB::table('users')->delete();


           // try to update user with id 4 - should fail
           $result = DB::table('users')->where('id', '=', 4)->update([
               'name' => 'John Snow',
               'email' => 'john.doe@example.com',
               'password' => Hash::make('password'),
           ]);
           if(!$result) {
               throw new \Exception('Failed to update user');
           }
       } catch (\Exception $e) {
           // dump the error message
           dump($e->getMessage());
           // rollback the transaction
           DB::rollBack();
           throw $e;
       }
   });


   return view('app');
});



Migrations

greg@mac laravel-database % php artisan make:migration create_comments_table

   INFO  Migration [database/migrations/2025_11_18_163943_create_comments_table.php] created successfully. 



   public function up(): void
   {
       Schema::create('comments', function (Blueprint $table) {
           // $table->engine = 'InnoDB'; // use InnoDB engine is default
           $table->id();
           $table->text('comment')->nullable(false);
           $table->unsignedBigInteger('user_id');
           // foreign key user_id references id column in users table
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           $table->timestamps();
       });
   }


greg@mac laravel-database % php artisan migrate:fresh --seed


Factories - for dummy data to load into tables


greg@mac laravel-database % php artisan make:factory CommentFactory

   INFO  Factory [database/factories/CommentFactory.php] created successfully.

greg@mac laravel-database % php artisan make:model Comment       

   INFO  Model [app/Models/Comment.php] created successfully. 

CommentFactory.php

class CommentFactory extends Factory
{
   /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
   public function definition(): array
   {
       return [
           'comment' => $this->faker->sentence(),
           //'user_id' => User::factory()->create()->id, // create a user and get the id
           'user' => $this->faker->numberBetween(1, 5),
           'created_at' => $this->faker->dateTime(),
           'updated_at' => $this->faker->dateTime(),
       ];
   }
}



How to run CommentFactory?

You need to create seeder for CommentFactory and run it from there

greg@mac laravel-database % php artisan make:seeder CommentSeeder

   INFO  Model [app/Models/CommentSeeder.php] created successfully.  


First of all your Comment model needs to have, otherwis laravel will not be able to associate CommentFactory with the model

Comment.php

class Comment extends Model
{
   use HasFactory;
}

CommentFactory.php

class CommentFactory extends Factory
{   
   public function definition(): array
   {
       return [
           'comment' => fake()->sentence(),
           'user_id' => fake()->numberBetween(1, 5),
       ];
   }
}




CommentSeeder.php


class CommentSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
       Comment::factory()->count(10)->create();
   }
}



And finally add this to DatabaseSeeder.php


class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
       $this->call(
           UserSeeder::class,
           CommentSeeder::class
       );
   }
}

Run because you have created new classes in laravel 
greg@mac laravel-database % composer dump-autoload

An now you nad load new dta to user and comments table

greg@mac laravel-database % php artisan optimize:clear 
php artisan optimize:clear clears all of Laravel’s caches at once.
It removes compiled, cached, or optimized files that may cause strange behavior during development.

greg@mac laravel-database % php artisan migrate:fresh --seed




Room table added

greg@mac laravel-database % php artisan make:migration create_rooms_table   

   INFO  Migration [database/migrations/2025_11_19_161518_create_rooms_table.php] created successfully.  

greg@mac laravel-database % php artisan make:factory RoomFactory   

   INFO  Factory [database/factories/RoomFactory.php] created successfully.  

greg@mac laravel-database % php artisan make:model Room 

   INFO  Model [app/Models/Room.php] created successfully.  

greg@mac laravel-database % php artisan make:seeder RoomSeeder

   INFO  Seeder [database/seeders/RoomSeeder.php] created successfully.  



migrations/2025_11_19_161518_create_rooms_table.php

   public function up(): void
   {
       Schema::create('rooms', function (Blueprint $table) {
           $table->id();
           $table->integer('size');
           $table->integer('price');
           $table->text('description');
           $table->timestamps();
       });
   }


RoomFactory.php

   public function definition(): array
   {
       return [
           'size' => fake()->numberBetween(1,2), //single or douple bed
           'price' => fake()->numberBetween(100,200), //single or douple bed
           'description' => fake()->text(1000)
       ];
   }


Model Room.php

class Room extends Model
{
   use HasFactory;
}


RoomSeeder.php

   public function run(): void
   {
       Room::factory()->count(10)->create();
   }
DatabaseSeeder.php

   public function run(): void
   {
       $this->call([
           UserSeeder::class,
           CommentSeeder::class,
           RoomSeeder::class
       ]
       );
   }

Then you call
greg@mac laravel-database %  composer dump-autoload
greg@mac laravel-database % php artisan optimize:clear
greg@mac laravel-database % php artisan migrate:fresh --seed // migrate and seed

  Dropping all tables ........................................................................... 18.92ms DONE
  Creating migration table ....................................................................... 9.36ms DONE

  0001_01_01_000000_create_users_table .......................................................... 42.40ms DONE
  0001_01_01_000001_create_cache_table .......................................................... 15.31ms DONE
  0001_01_01_000002_create_jobs_table ........................................................... 37.51ms DONE
  2025_11_18_163943_create_comments_table ....................................................... 24.11ms DONE
  2025_11_19_161518_create_rooms_table ........................................................... 5.72ms DONE

   INFO  Seeding database.  

  Database\Seeders\UserSeeder .................................................................... 193 ms DONE  
  Database\Seeders\CommentSeeder .................................................................. 11 ms DONE  
  Database\Seeders\RoomSeeder ...................................................................... 6 ms DONE 


Reservation Table added

Just call this with one call 

greg@mac laravel-database % php artisan make:model Reservation -mfs

What the flags mean:
-m → creates a migration (table file)
-f → creates a factory
-s → creates a seeder


   INFO  Model [app/Models/Reservation.php] created successfully.  

   INFO  Factory [database/factories/ReservationFactory.php] created successfully.  

   INFO  Migration [database/migrations/2025_11_19_172331_create_reservations_table.php] created successfully.  

   INFO  Seeder [database/seeders/ReservationSeeder.php] created successfully.  

DB queries

   $users = DB::table('users')->get();
   $users = DB::table('users')->pluck('email'); // jget all users but onluy emails
   $user = DB::table('users')->where('name', 'Mrs. Odie Metz')->first(); // just one result
   $user = DB::table('users')->where('name', 'Mrs. Odie Metz')->value('email');
   $user = DB::table('users')->find(1); // get by id = 1 user id


   $comments= DB::table('comments')->select('comment as comment_content')->get(); // sellect fields
   $comments= DB::table('comments')->select('user_id')->distinct()->get(); // only unique ids


   $result = DB::table('comments')->count(); // how many comments
   $result = DB::table('comments')->get(); // get all comments
   $result = DB::table('comments')->max('user_id'); // returns the maximum value of the user_id column iside comments table
   $result = DB::table('comments')->sum('user_id'); // sum off all numbers inside user_id 1+2+3+4+5 ect


   $result = DB::table('comments')->where('comment', 'abc')->exists(); // where comment with text 'abc exists
   $result = DB::table('comments')->where('comment', 'abc')->doesntExist(); // where comment with text 'abc doesnt exists


   dump($result);


   $result = DB::table('rooms')->get();
   $result = DB::table('rooms')->where('price','<',200)->get(); // = like, etc.




   // select * from `rooms` where (`size` = '2' and `price` < '400')
   $result = DB::table('rooms')->where([
       ['size', '2'],
       ['price', '<', '400'],
   ])->get();


   // select * from `rooms` where `size` = '2' or `price` < '400'
   $result = DB::table('rooms')
       ->where('size' ,'2')
       ->orWhere('price', '<' ,'400')
       ->get();


   // select * from `rooms` where `price` < '400' or (`size` > '1' and `size` < '4')
   $result = DB::table('rooms')
           ->where('price', '<' ,'400')
           ->orWhere(function($query) {
               $query->where('size', '>' ,'1')
                     ->where('size', '<' ,'4');
           })
           ->get();








Where clouses
 
   // select * from `rooms` where `size` between 1 and 3
   $result = DB::table('rooms')
           ->whereBetween('size',[1,3]) // whereNotBetween
           ->get();


   // select * from `rooms` where `id` not in (1, 2, 3)           
   $result = DB::table('rooms')
           ->whereNotIn('id',[1,2,3]) // whereIn
           ->get();


   // whereNull('column')  whereNotNull // where specific column is null or notNull
   // whereDate('created_at', '2025-11-19')
   // whereMonth('created_at', '11')
   // whereDay('created_at', '19')
   // whereYear('created_at', '2025')
   // whereTime('created_at', '=', '12:25:10')
   // whereColumn('column1', '>', 'column2')
   // whereColumn([ // in array means all conditions are linked with - 'and'
   //     ['first_name', '=', 'last_name'],
   //     ['updated_at', '>', 'created_at']
   // ]


   // select * from `users` where exists (select `id` from `reservations` where reservations.user_id = users.id and `check_in` = '2025-11-19' limit 1)
   // lookign for users who check in date equals to 2025-11-19
   $result = DB::table('users')
          ->whereExists(function ($query) {
              $query->select('id')
                    ->from('reservations')
                    ->whereRaw('reservations.user_id = users.id')
                    ->where('check_in', '=', '2025-11-19')
                    ->limit(1); // just need one reservation to qualify
          })
          ->get(); // looking for all users


   dump($result);
  
Json Where clauses
Json where clauses - Used for storing metadata - like extra information about data 
You woud drop into 1 column as json called meta
Something like that 

{
  "id": 15,
  "name": "Greg",
  "email": "greg@example.com",
  "meta": {
    "last_login": "2025-11-18 19:20:00",
    "signup_ip": "192.168.0.10",
    "device": "MacBook Pro",
    "preferred_language": "en"
  }
}


https://laravel.com/docs/11.x/queries#json-where-clauses


User.php
   protected function casts(): array
   {
       return [
           'email_verified_at' => 'datetime',
           'password' => 'hashed',
           'meta' => 'json',
       ];
   }

UserFactory.php

       return [
           'name' => fake()->name(),
           'email' => fake()->unique()->safeEmail(),
           'email_verified_at' => now(),
           'password' => static::$password ??= Hash::make('password'),
           'remember_token' => Str::random(10),
           'meta' => [
               'settings' => [
                   'site_background' => 'black',
                   'site_language' => 'en',
               ],
               'skills' => fake()->randomElements(['Laravel', 'PHP 7', 'Wordpress', 'HTML 5', 'CSS', 'ReactJS'], mt_rand(1,6)),
               'gender' => fake()->randomElement(['Male', 'Female''])
           ]
       ];


0001_01_01_000000_create_users_table.php


       Schema::create('users', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->string('email')->unique();
           $table->timestamp('email_verified_at')->nullable();
           $table->string('password');
           $table->json('meta')->nullable();
           $table->rememberToken();
           $table->timestamps();
       });


greg@mac laravel-database % php artisan migrate:fresh --seed

Json Where clauses


   // select * from `users` where json_contains(`meta`, '\"Laravel\"', '$."skills"')
   $result = DB::table('users')
               ->whereJsonContains('meta->skills', 'Laravel')
               ->get();


   // select * from `users` where json_contains(`meta`, '\"en\"', '$."settings"."site_language"')
   $result = DB::table('users')
               ->whereJsonContains('meta->settings->site_language', 'en')
               ->get();


   dump($result);


Pagination
   $result = DB::table('comments')->paginate(4);
   $result = DB::table('comments')->simplePaginate(4);


   dump($result);
Fulltext Index


ALTER TABLE comments ADD FULLTEXT fulltext_index(comment)
$result = DB::statement('ALTER TABLE comments ADD FULLTEXT fulltext_index(comment)'); // MySQL >= 5.6


A FULLTEXT index on the comment column gives you fast, intelligent text searching, much better than using LIKE '%word%'.
Here are the advantages:

✅ 1. Much faster searching large text fields
Without FULLTEXT:
SELECT * FROM comments WHERE comment LIKE '%apple%';

This does a slow full table scan.
With FULLTEXT:
SELECT * FROM comments
WHERE MATCH(comment) AGAINST('apple');

MySQL uses the FULLTEXT index, which is extremely faster—especially with thousands or millions of rows.


