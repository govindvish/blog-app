<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
    	// passing a value
    	$title = "Welcome.!!";
    	// return view('pages.index',compact('title'));
    	return view('pages.index')->with('title', $title);
    }

    public function about(){
    	$title = "About Us.!!";
    	return view('pages.about')->with('title', $title);
    }

    public function services(){
    	// passing multiple values
    	$data = [
    		'title' => 'Services.!!',
    		'services' => [
    			'Web Design',
    			'SEO',
    			'Programming'
    		]
    	];
    	return view('pages.services')->with($data);
    }
}
