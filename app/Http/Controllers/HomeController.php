<?php

namespace App\Http\Controllers;
use resources\views\front\home;
use App\Models\Category;
use App\Models\Job;


use Illuminate\Http\Request;

class HomeController extends Controller
{
   //This method will show our home page
   public function index()
   {


     $categories = Category::where('status',1)
     ->orderBy('id','ASC')->take(8)->get();

     $featuredJobs = Job::where('status',1)
     ->orderBy('created_at','DESC')
     ->with('jobType')
     ->where('isfeatured',1)->take(6)->get();

     $latestJobs = Job::where('status',1)
     ->with('jobType')
     ->orderBy('created_at','DESC')
     ->take(6)->get();

     return view('front.home',[
   'categories'=> $categories,
   'featuredJobs'=> $featuredJobs,
   'latestJobs'=> $latestJobs,
   


]);
   }

}