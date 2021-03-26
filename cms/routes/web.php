<?php

use App\Http\Controllers\PostsController;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use App\Models\Photo;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/post/{id}', [PostController::class, 'show_post']);

//Route::get('/insert', function () {
//    DB::insert('insert into posts(title, body) values (?, ?)', ['PHP with Laravel', 'PHP Laravel is the best thing that has happened to PHP.']);
//});
//
//Route::get('/read', function (){
//    $results = DB::select('select * from posts where id = ?', [1]);
//
//    foreach ($results as $post) {
//        return var_dump($results);
//    }
//});
//
//Route::get('/update', function (){
//    $updated = DB::update('update posts set title = ? where id = ?', ['PHP with Symfony', 1]);
//    return $updated;
//});
//
//Route::get('/delete', function (){
//    $deleted = DB::delete('delete from posts where id = ?', [1]);
//    return $deleted;
//});

//Route::get('/find', function (){
//    $posts = Post::all();
//    foreach ($posts as $post){
//        return $post->title;
//    }
//});

//Route::get('/find', function (){
//    $post = Post::find(2);
//    return $post->title;
//});

//Route::get('/findwhere', function (){
//    $post = Post::where('id',2)->orderBy('id','desc')->take(1)->get();
//    return $post;
//});

//Route::get('/findmore', function (){
//    $posts = Post::findOrFail(2);
//
//    return $posts;
//});
//
//Route::get('/', function () {
//    return view('welcome');
//});

/*
 * ELOQUENT RELATIONSHIPS
 *
 */
//
//Route::get('/user/{id}/post', function($id){
//    return User::find($id)->post;
//});
//
//Route::get('/post/{id}/user', function($id){
//   return Post::find($id)->user;
//});

//One to Many Relationship

//Route::get('/posts', function(){
//    $user = User::find(1);
//
//    foreach($user->posts as $post){
//        echo $post->title . '<br>';
//    }
//});

//Many to Many

//Route::get('user/{id}/role', function ($id){
//    $user = User::find($id)->roles()->orderBy('id','desc')->get();
//
//    return $user;

//    foreach ($user->roles as $role) {
//        return $role->name;
//    }
//});

//Accessing intermediate table

//Route::get('/user/pivot', function (){
//    $user = User::find(1);
//
//    foreach ($user->roles as $role) {
//        return $role->pivot;
//    }
//});
//
//Route::get('/user/country', function(){
//    $country = Country::find(2);
//
//    foreach ($country->posts as $post) {
//        return $post->title;
//    }
//});

/*
 * Polymorphic Relationships
 */

//Route::get('/post/{id}/photos', function ($id){
//   $post = Post::find($id);
//
//   foreach ($post->photos as $photo)
//   {
//       echo $photo->path;
//   }
//});

//Route::get('/photo/{id}/post', function ($id){
//    $photo = Photo::findOrFail($id);
//    return $photo->imagable;
//
//
//});
//
////Polymorphic Many to Many
//
//Route::get('/post/tag', function (){
//   $post =  Post::find(1);
//
//   foreach ($post->tags as $tag) {
//       return $tag->name;
//   }
//});
//
//Route::get('/img/post', function (){
//    $tag = Tag::find(2);
//
//    foreach ($tag->posts as $post) {
//        echo $post;
//    }
//});
//

//Route::get('/', function(){
//
//});

Route::resource('/posts', PostsController::class);
