<?php

namespace App\Http\Controllers\Admin;// altera o namespace para entrar em admim
use App\Http\Controllers\Controller;// funcione corretamente o meu users controller
use Illuminate\Http\Request;
use App\User;// ter acesso ao banco de dados
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::all();// dessa forma pego todos os usuarios
        $users = User::paginate(6);// dessa forma pego todos os usuarios

        return view('admin.users.index',[// mandar um array de exibição
            'users'=>$users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data =$request->only([
            'name',
            'email',
            'password',
            'password_confirmation'
        ]);

            $validator = Validator::make($data,[// fazer a verificação
             'name'=>['required','string','max:100'],
             'email'=>['required','string','max:200','unique:users'],
             'password'=>['required','string','min:4','confirmed']
            ]);// ele ja esta fazendo o processo de validação


                if($validator->fails()){
                    return redirect()->route('users.create')
                    ->withErrors($validator)
                    ->withInput();// para ele voltar os campos
                }
                // $user = User::create();
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = Hash::make($data['password']);
                $user->save(); // por fim salvar

                return redirect()->route('users.index');// listagem

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)// mostrar um item especifico
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if($user){
        return view('admin.users.edit',[
            'user'=>$user // mandar o usuario que eu achei
        ]);
        }
        return redirect()->route('users.index'); // vai voltar para lista
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);// receber um usuario
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
                    return redirect()->route('users.edit',[
                                 'user'=>$id
                            ])->withErrors($validator); // nofinalde tudo ele faz o redirect
                }

            $user->save();
        }

        return redirect()->route('users.index'); // vai voltar para lista

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
