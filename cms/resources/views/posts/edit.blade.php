@extends('layouts.app')

@section('content')
    <h1>Edit Post</h1>

    <form action="/posts/{{$post->id}}" method="post">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <input type="text" name="title" value="{{$post->title}}" placeholder="Enter Title">
        <input type="submit" name="submit">
    </form>
@endsection
