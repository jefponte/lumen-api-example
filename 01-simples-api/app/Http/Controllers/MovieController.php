<?php

namespace App\Http\Controllers;

class MovieController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function index(){
         return [
             "The Matrix",
             "Django"
         ];
     }

    //
}
