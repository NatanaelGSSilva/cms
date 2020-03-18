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
        echo "Recebendo os dados";
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
