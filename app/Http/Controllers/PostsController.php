<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostsController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        // Get all post
        // $posts = Post::all();
        // To order post in asc/desc order
        $posts = Post::orderBy('created_at', 'desc')->get();
        // Add pagination
        // $posts = Post::orderBy('title', 'desc')->paginate(1);
        // To get post using where condition
        // $posts = Post::where('title', 'Post Two')->get();
        // Add limit to query
        // $posts = Post::orderBy('title', 'desc')->take(1)->get();
        // Using sql syntax we need to import DB
        // $posts = DB::select('SELECT * FROM posts');
        // return $posts;
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            // Upload image and store it
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        // to get current user's id.
        $post->user_id = auth()->user()->id;
        // to get file
        $post->cover_image = $fileNameToStore;
        $post->save();
        
        // Redirect to post with success message.
        return redirect('/posts')->with('success', 'Post Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        // return Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        // Check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unathorized Page');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required'
        ]);

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            // Upload image and store it
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }

        // Update Post
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');

        // To avoid replacing image with noimage.jpg is user does not upload image while updating.
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();
        
        // Redirect to post with success message.
        return redirect('/posts')->with('success', 'Post Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        // Check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unathorized Page');
        }

        if($post->cover_image != 'noimage.jpg'){
            // Delete Image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }

        $post->delete();

        // Redirect to post with success message.
        return redirect('/posts')->with('success', 'Post Deleted.');
    }
}
