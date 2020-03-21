<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use app\User;
class ProfileController extends Controller
{
public function __construct(){
    $this->middleware('auth');// independente de qualquer coisa ocara tem que estar logado

    }

    public function index(){
        $loggedId = intval(Auth::id());// pegar o id usuario logado

        $user = User::find($loggedId); // pegar o usuario especifico
        if($user){// se ele achar faz alguma coisa
            return view("admin.profile.index",[
            'user' => $user
            ]);// tem que mandar em forma de array

        }
        return redirect()->route('admin');
    }
}
