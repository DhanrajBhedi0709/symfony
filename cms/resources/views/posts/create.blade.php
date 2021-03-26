@extends('layouts.app')

@section('content')
    <h1>Add Post</h1>

    <form action="/posts" method="post">
        @csrf
        <input type="text" name="title" placeholder="Enter Title">
        <input type="submit" name="submit">
    </form>
@endsection
