<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function imageupload(Request $request){

        $request->validate([
            'file'=>'required|image|mimes:jpeg,jpg,png'
        ]);

            $imageName = time().'.'.$request->file->extension(); // gerar um nome aleatorio

            $request->file->move(public_path('media/images'),$imageName);

            return [
                'location'=>asset('media/images/'.$imageName)// link completo da minha imagem
            ];
    }
}
