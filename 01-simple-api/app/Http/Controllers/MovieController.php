<?php

namespace App\Http\Controllers;

class MovieController extends Controller
{
    public function index(){
        return [
            "The Matrix",
            "Django"
        ];
    }
}
