@extends('layouts.app')

@section('content')
	<h1>{{$post->title}}</h1>
	<img style="width:150px" src="/storage/cover_images/{{$post->cover_image}}" alt="">
	{{-- to parse html --}}
	<div class="mt-3">{!!$post->body!!}</div>
	<hr>
	<small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
	
	<div class="mt-3">
		<a href="/posts" class="btn btn-outline-secondary">Go Back</a>
		@if(!Auth::guest())
			@if(Auth::user()->id == $post->user_id)
				<a href="/posts/{{$post->id}}/edit" class="btn btn-outline-secondary">Edit</a>

				{!! Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST']) !!}
					{{Form::hidden('_method', 'DELETE')}}
					{{Form::submit('Delete', ['class' => 'btn btn-outline-danger mt-3 d-inline'])}}
				{!! Form::close() !!}
			@endif
		@endif
	</div>
	
@endsection