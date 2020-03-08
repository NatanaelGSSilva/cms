<?php

namespace App\Http\Controllers\Admin; // primeira coisa a fazer

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct(){
        $this->middleware('auth');// todo esse controlle dele todas as actions dele estajam baseada na autentificação o cara tem que estar logado para acessar ele
    }
    public function index(){
        return view('admin.home');
    }
}
