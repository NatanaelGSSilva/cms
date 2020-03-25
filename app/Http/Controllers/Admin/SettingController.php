<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;// para poder extender
use Illuminate\Http\Request;
use App\Setting;// model que vamos estar utilizando
use Illuminate\Support\Facades\Validator;


class SettingController extends Controller
{
    public function __construct(){
        $this->middleware('auth');// independente de qualquer coisa ocara tem que estar logado
        }

    public function index(){// listagem das nossas configurações e salvar as configurações
        $settings = [];

        $dbsettings = Setting::get();// ele pega todo mundo, find sem argumento nao existe
        foreach($dbsettings as $dbsetting){
            $settings[$dbsetting['name']] = $dbsetting['content'];// organiza o array
        }

        return view('admin.settings.index',[
            'settings'=>$settings// ele manda pra settings
        ]);
    }

    public function save(Request $request){// que é o que esta recebendoas propriedades as informacoes
        $data = $request->only([// receber as informacoes do formulario
            'title','subtitle','email','bgcolor','textcolor'
        ]);
            $validator = $this->validator($data);

            if($validator->fails()){
                return redirect()->route('settings')
                ->withErrors($validator);
            }
            foreach ($data as $item => $value) {// alterar um item
                Setting::where('name',$item)->update([
                    'content'=>$value
                ]);
            }

            return redirect()->route('settings')
            ->with('warning','Informações alteradas com sucesso!');
    }


    protected function validator($data){
        return Validator::make($data,[
            'title'=>['string','max:100'],
            'subtitle'=>['string','max:100'],
            'email'=>['string','max:100'],
            'bgcolor'=>['string','regex:/#[a-zA-Z0-9]{6}/i'],
            'textcolor'=>['string','regex:/#[a-zA-Z0-9]{6}/i']
        ]);
    }
}
