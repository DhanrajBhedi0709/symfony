<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/post/{id}', [PostController::class, 'show_post']);

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

//Route::get('/basicinsert', function (){
//    $post = new Post;
//
//    $post->title = "New Eloquent title inserted";
//    $post->body = "Wow eloquent is really cool, look at this content";
//
//    $post->save();
//});

Route::get('/basicupdate', function (){
    $post = Post::find(4);

    $post->title = "New Eloquent title inserted 2";
    $post->body = "Wow eloquent is really cool, look at this content 2";

    $post->save();
});
