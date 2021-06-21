<?php

namespace App\Http\Controllers;

class SeriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function index(){
         return [
             "Loki",
             "Ducktales"
         ];
     }

    //
}
