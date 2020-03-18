<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//  use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;// importar o validater como é um facades
use Illuminate\Support\Facades\Auth;
 use App\Providers\RouteServiceProvider;




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

    //  use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/painel';

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
        // $tries = $request->session()->get('login_tries',0);//contabilizaçãode quantas tentativas seram feitas pegando informações da sessao
        // // $frase = __('messages.test');
        // return view('login', [
        //     'tries' => $tries
        // ]);
        return view('admin.login');
    }

    public  function authenticate(Request $request){// funçãode autentificacao
    // //     // processo de login pegar os campos de email e senha
    // //     $creds = $request->only(['email','password']);
        $data = $request->only([// pegar os dados dos campos
            'email',
            'password'
        ]);
        $validator = $this->validator($data);

        $remember = $request->input('remember',false);

        if($validator->fails()){
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        if(Auth::attempt($data,$remember)){//mandar o data que sao os dados armazenados na variavel
            return  redirect()->route('admin');
        }else{
            $validator->errors()->add('password','E-mail e/ou senha errados');

            return redirect()->route('login')
            ->withErrors($validator)
            ->withInput();
            }
                        
        }
    // //     // $request->session()->forget('login_tries'); destruir uma sessão
    // //     if(Auth::attempt($creds)){// fazer a tentativa de login
    // //         $request->session()->put('login_tries',0);// acertou subtitui por 0
    // //             return  redirect()->route('config.index');
    // //     }else{
    // //         $tries = intval($request->session()->get('login_tries',0));
    // //         // $tries++;
    // //         $request->session()->put('login_tries',++$tries);// saber a quantidade de tentativas
    // //         return redirect()->route('login')->with(
    // //             'warning','E-mail e/ou senha inválidos.'
    // //         );
    // //     }
    

    public function logout(){
        Auth::logout();
        return redirect()->route('login');// mandar para login
    }

    protected function validator(array $data){// função protegida de validacao
        return Validator::make($data,[
            'email'=>['required','string','email','max:100'],
            'password'=>['required','string','min:4']
            
        ]);
    }
}
