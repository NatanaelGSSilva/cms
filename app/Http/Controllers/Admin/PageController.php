<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Page;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{

    public function __construct(){
        $this->middleware('auth');// todo esse controlle dele todas as actions dele estajam baseada na autentificação o cara tem que estar logado para acessar ele

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::all();// dessa forma pego todos os usuarios
        $pages = Page::paginate(10);// dessa forma pego todos os usuarios


        return view('admin.pages.index',[// mandar um array de exibição
            'pages'=>$pages

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
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
            'title',
            'body'

        ]);

        $data['slug'] =Str::slug($data['title'],'-');// um a gente cria


            $validator = Validator::make($data,[// fazer a verificação
             'title'=>['required','string','max:100'],
             'body'=>['string'],
             'slug'=>['required','string','max:100','unique:pages']
            ]);// ele ja esta fazendo o processo de validação


                if($validator->fails()){
                    return redirect()->route('pages.create')
                    ->withErrors($validator)
                    ->withInput();// para ele voltar os campos
                }


                // $user = User::create();
                $page = new Page;
                $page->title = $data['title'];
                $page->slug = $data['slug'];
                $page->body = $data['body'];
                $page->save(); // por fim salvar

                return redirect()->route('pages.index');// listagem
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        //
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
        //
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
