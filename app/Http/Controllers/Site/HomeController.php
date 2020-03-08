<?php

namespace App\Http\Controllers\Site;// primeira coisa a fazer
use App\Http\Controllers\Controller;// Segunda coisa a fazer importar o controller 
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('site.home');
    }
}
