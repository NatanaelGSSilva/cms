<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use app\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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


    public function save(Request $request)
    {
        $loggedId = intval(Auth::id());// pegar o id usuario logado
        $user = User::find($loggedId);// pegar o id do usuario que esta logado especifico
        if($user){// verificar se é um usuario existente
            $data = $request->only([
                'name',
                'email',
                'password',
                'password_confirmation'
            ]);// pegar os dados dos campos
            $validator = Validator::make([
                'name'=>$data['name'],
                'email'=>$data['email']
            ],[ // que locura isso(regras que a gente precisa validar)
                'name'=>['required','string','max:100'],
                'email'=>['required','string','email','max:100']// validação geral

            ]);// fazer as validações se falhou algo

                // if($validator->fails()){
                //     return redirect()->route('users.edit',[
                //         'user'=>$id
                //     ])->withErrors($validator);
                // }

            // 1.alteração do nome,
                $user->name = $data['name'];// alterar o nome do cara


            //2. alteração do email
            //2.1 primeiro verificamos se o email foi alterado
                if($user->email != $data['email']){// que dizer que o email foi alterado
                     // 2.2 verificamos se o novo email ja existe
                     $hasEmail = User::where('email',$data['email'])->get();// verificar se tem um usuario com aquele email
                     // 2.3 se não existir nos alteramos
                     if(count($hasEmail)===0){
                         $user->email = $data['email'];// alterar o email
                     }else{
                        $validator->errors()->add// mensagem de erro caso noa tenha 4 caracteres
                        ('email',__('validation.unique',[
                            'attribute'=>'email'

                        ]));
                     }

                }

            // 3.0 alteração da senha
            // 3.1 Verifica se o usuario digitou senha
                if(!empty($data['password'])){
                    // 3.2 verifica se a confirmação esta ok
                    if(strlen($data['password'])>=4){// verifica se a senha digitada e maior que 4
                    if($data['password'] === $data['password_confirmation']){
                         // 3.3 altera a senha
                        $user->password = Hash::make($data['password']);// troca a senha
                    }else{
                        $validator->errors()->add// mensagem de erro caso noa tenha 4 caracteres
                        ('password',__('validation.confirmed',[
                            'attribute'=>'password'

                        ]));
                    }
                }else{
                    $validator->errors()->add// mensagem de erro caso noa tenha 4 caracteres
                    ('password',__('validation.min.string',[
                        'attribute'=>'password',
                        'min'=>4
                    ]));


                }

                }
                if(count( $validator->errors())>0){
                    return redirect()->route('profile',[
                                 'user'=>$loggedId
                            ])->withErrors($validator); // nofinalde tudo ele faz o redirect
                }

            $user->save();

                return redirect()->route('profile')
                ->with('warning','Informações alteradas com sucesso!');
        }

        return redirect()->route('profile');

    }


}
