@extends('layouts.app')

@section('content')
	<h1>Posts</h1>
	@if(count($posts) > 0)
		<div class="list-group py-3">
		@foreach($posts as $post)
			<div class="list-group-item mb-3">
				<div class="row">
					<div class="col-md-4 col-sm-4">
						<img style="width:80px" src="/storage/cover_images/{{$post->cover_image}}" alt="">
					</div>
					<div class="col-md-8 col-sm-8">
						<h3 class="pt-2"><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
						<small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
					</div>
				</div>		
			</div>
		@endforeach
		</div>
		{{-- For pagination --}}
		{{-- {{$posts->links()}} --}}
	@else
		<p>No post found.!</p>
	@endif
@endsection