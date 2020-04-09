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
    public function edit($id) // mostra o formulario de edição
    {
        $page = Page::find($id);// procura a pagina
        if($page){// achou o id
        return view('admin.pages.edit',[
            'page'=>$page // mandar as informaç~es da pagina
        ]);
        }
        return redirect()->route('pages.index'); // vai voltar para lista
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

        $page = Page::find($id);// receber uma pagina
        if($page){// verificar se é um usuario existente
            $data = $request->only([
                'title',
                'body'

            ]);// pegar os dados dos campos

            if($page['title'] !== $data['title']){// alterou o titulo
                $data['slug'] = Str::slug($data['title'],'-');// adicionei o slug

                $validator = Validator::make($data,[
                    'title'=>['required','string','max:100'],
                    'body'=>['string'],
                    'slug' =>['required','string','max:100','unique:pages']
                    ]);
            }else{
                $validator = Validator::make($data,[// validador basicao para quando eu nao alterei o titulo
                'title'=>['required','string','max:100'],
                'body'=>['string']
                ]);
            }

            if($validator->fails()){// caso deu problema
                return redirect()->route('pages.edit',[
                    'page' =>$id
                ])
                ->withErrors($validator)
                ->withInput();// para ele voltar os campos
            }


            // 1.alteração da pagina,
                $page->title = $data['title'];// alterar o nome da pagina
                // $page->slug = $data['slug'];// alterar o nome da pagina
                 $page->body= $data['body'];

                 if(!empty($data['slug'])){// verificar o proprio slug
                //  if($page['slug'] !== $data['slug']){// verificar o proprio slug
                      $page->slug = $data['slug'];// alterar o nome da pagina
                 }

            $page->save();
        }

        return redirect()->route('pages.index'); // vai voltar para lista


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $page = Page::find($id); // pegar o id da pagina
        $page->delete();// deletar essa pagina
        return redirect()->route('pages.index');

    }
}
