<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/config';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(Request $request){
        $tries = $request->session()->get('login_tries',0);//contabilizaçãode quantas tentativas seram feitas pegando informações da sessao
        // $frase = __('messages.test');
        return view('login', [
            'tries' => $tries
        ]);
    }

    public  function authenticate(Request $request){
        // processo de login pegar os campos de email e senha
        $creds = $request->only(['email','password']);

        
        // $request->session()->forget('login_tries'); destruir uma sessão
        if(Auth::attempt($creds)){// fazer a tentativa de login
            $request->session()->put('login_tries',0);// acertou subtitui por 0
                return  redirect()->route('config.index');
        }else{
            $tries = intval($request->session()->get('login_tries',0));
            // $tries++;
            $request->session()->put('login_tries',++$tries);// saber a quantidade de tentativas
            return redirect()->route('login')->with(
                'warning','E-mail e/ou senha inválidos.'
            );
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
